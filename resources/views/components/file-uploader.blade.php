@props([
    'filepath' => 'filepath', // input name for temporary path - this hold file path where the file stored in 'private/temp'
    'original' => 'original', // input name for original name - 'abc.pdf'
    'maxSize' => 500, // Default 500MB
    'accept' => '*/*',
    'uploadUrl' => '/upload-async-file', // AJAX target endpoint
    'deleteUrl' => '/delete-async-file',
])

<div x-data="fileUploadComponent({ 
            filepath: '{{ $filepath }}', 
            original: '{{ $original }}', 
            maxSize: {{ $maxSize }}, 
            uploadUrl: '{{ $uploadUrl }}',
            deleteUrl: '{{ $deleteUrl }}'
        })"
        {{ $attributes->merge(['class' => 'w-full max-w-md mx-auto p-4 bg-white rounded-lg shadow-sm border border-gray-200']) }} 
    >
    
    <!-- Hidden input that actually submits to your main form -->
    <input type="hidden" :name="filepath" id= "filepath" x-model="uploadedFilePath">
    <input type="hidden" :name="original" id= "original" x-model="uploadedFileName">

    <!-- Hidden Native File Input (Used only for file selection) -->
    <input type="file"
            id="native-file"
            :accept="'{{ $accept }}'"
            x-ref="fileInput" 
            @change="handleFileSelect" 
            :disabled="uploadedFilePath || isUploading"
            class="hidden" />

    <!-- Drag & Drop / Click Zone (Visual and functional blocks) -->
    <div @click="!uploadedFilePath && !isUploading && $refs.fileInput.click()"
         @dragover.prevent="!uploadedFilePath && !isUploading && (isDragging = true)"
         @dragleave.prevent="isDragging = false"
         @drop.prevent="isDragging = false; !uploadedFilePath && !isUploading && handleFileDrop($event)"
         :class="[
            isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 bg-gray-50',
            (uploadedFilePath || isUploading) ? 'cursor-not-allowed opacity-60 bg-gray-100 select-none' : 'cursor-pointer'
         ]"
         class="border-2 border-dashed rounded-lg p-6 text-center transition-all duration-200">
        
        <!-- Idle State View -->
        <div class="space-y-2" x-show="!file">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="text-sm text-gray-600">
                <span class="font-medium text-indigo-600 hover:text-indigo-500">Click to upload</span> or drag and drop
            </p>
            <p class="text-xs text-gray-500">Max size: {{ $maxSize }}MB</p>
        </div>

        <!-- Selected File View -->
        <div x-show="file" class="text-left" x-cloak>
            <div class="flex items-center justify-between">
                <span x-text="file ? file.name : ''" class="text-sm font-medium text-gray-700 truncate max-w-[200px]"></span>
                <span x-text="formatSize(file ? file.size : 0)" class="text-xs text-gray-500"></span>
            </div>
        </div>
    </div>

    <!-- Error Message Display -->
    <p x-show="errorMessage" x-text="errorMessage" class="mt-2 text-xs text-red-600 font-medium" x-cloak></p>

    <!-- Progress Bar Section -->
    <div x-show="isUploading" class="mt-4 space-y-2" x-cloak>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-100 ease-out" 
                 :style="'width: ' + progress + '%'"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
            <span x-text="'Uploading... ' + progress + '%'"></span>
            <button type="button" @click.stop="cancelUpload" class="text-red-500 hover:underline">Cancel</button>
        </div>
    </div>

    <!-- Success / Finished State + Delete Button -->
    <div x-show="isFinished" class="mt-3 flex items-center justify-between bg-green-50 p-2 rounded-lg border border-green-200" x-cloak>
        <div class="text-xs text-green-700 font-medium flex items-center gap-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Upload complete! To change files, click delete.
        </div>
        
        <!-- Delete Button -->
        <button type="button" 
                @click="deleteUploadedFile" 
                :disabled="isDeleting"
                class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-medium disabled:opacity-50 transition-colors">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span x-text="isDeleting ? 'Deleting...' : 'Delete'"></span>
        </button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fileUploadComponent', (config) => ({
        filepath: config.filepath,
        original: config.original,
        maxSize: config.maxSize,
        uploadUrl: config.uploadUrl,
        deleteUrl: config.deleteUrl,
        file: null,
        isDragging: false,
        isUploading: false,
        isFinished: false,
        isDeleting: false,
        progress: 0,
        errorMessage: '',
        uploadedFilePath: '', // Stores final path returned by server
        uploadedFileName: '',
        xhr: null,

        handleFileSelect(e) {
            if (e.target.files.length) {
                this.validateAndUpload(e.target.files[0]); // Explicitly grab single item reference
            }
        },

        handleFileDrop(e) {
            if (e.dataTransfer.files.length) {
                let selectedFile = e.dataTransfer.files[0];
                let acceptTypes = document.getElementById('native-file').getAttribute('accept').split(',');
                let accept = false;
                for (let idx = 0; idx < acceptTypes.length; idx++) {
                    if (selectedFile.name.endsWith(acceptTypes[idx]) || type == '*/*') {
                        accept = true;
                        break;
                    }
                }
                if (!accept) {
                    this.errorMessage = 'THis file type is not allowed.';
                    return;
                }

                this.validateAndUpload(selectedFile); // Explicitly grab single item reference
            }
        },

        validateAndUpload(selectedFile) {
            // CRITICAL STEP: Strict logic barrier preventing multi-file stacking
            if (this.uploadedFilePath || this.isUploading) {
                this.errorMessage = 'An uploaded file is already present. Delete it to upload a new one.';
                return;
            }

            this.resetState();
            this.file = selectedFile;

            // Validate Size (MB to Bytes conversion)

            if (this.file.size > this.maxSize * 1024 * 1024) {
                this.errorMessage = 'File exceeds the maximum limit of ' + this.maxSize + 'MB.';
                this.file = null;
                return;
            }

            this.startUpload();
        },

        startUpload() {
            this.isUploading = true;
            this.xhr = new XMLHttpRequest();
            
            let formData = new FormData();
            formData.append('file_payload', this.file); // Uniform key for the file parser endpoint
            
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) {
                formData.append('_token', tokenMeta.getAttribute('content'));
            }

            this.xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    this.progress = Math.round((e.loaded / e.total) * 100);
                }
            });

            this.xhr.onload = () => {
                if (this.xhr.status >= 200 && this.xhr.status < 300) {
                    try {
                        const response = JSON.parse(this.xhr.responseText);
                        this.uploadedFilePath = response.path; // Set field path for the main form layout
                        this.uploadedFileName = this.file.name;
                        this.isFinished = true;
                    } catch (e) {
                        this.handleError('Invalid server response format - check file size');
                    }
                    this.isUploading = false;
                } else {
                    this.handleError('Upload failed. Server error.');
                }
            };

            this.xhr.onerror = () => this.handleError('Network error occurred.');
            
            this.xhr.open('POST', this.uploadUrl);
            this.xhr.send(formData);
        },

        deleteUploadedFile() {
            if (!this.uploadedFilePath || this.isDeleting) return;

            if (!confirm('Are you sure you want to delete this file?')) return;

            this.isDeleting = true;
            
            fetch(this.deleteUrl, {
                method: 'POST', // Using POST with _method mapping for Laravel route handling
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    _method: 'DELETE',
                    path: this.uploadedFilePath
                })
            })
            .then(async (response) => {
                if (response.ok) {
                    this.resetState();
                    // Clear file input value to allow uploading the same file again
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                } else {
                    const data = await response.json();
                    this.errorMessage = data.error || 'Failed to delete file from server.';
                }
            })
            .catch(() => {
                this.errorMessage = 'Network error during deletion.';
            })
            .finally(() => {
                this.isDeleting = false;
            });
        },

        cancelUpload() {
            if (this.xhr) {
                this.xhr.abort();
                this.resetState();
                if (this.$refs.fileInput) this.$refs.fileInput.value = '';
            }
        },

        handleError(msg) {
            this.errorMessage = msg;
            this.isUploading = false;
            this.progress = 0;
            this.file = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        resetState() {
            this.file = null;
            this.progress = 0;
            this.isUploading = false;
            this.isFinished = false;
            this.isDeleting = false;
            this.errorMessage = '';
            this.uploadedFilePath = '';
            this.uploadedFileName = '';
        },

        formatSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }));
});
</script>

