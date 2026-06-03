<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Manuscripts') }}
        </h2>
    </x-slot>

@push('after-styles')
    <link href="/css/manuscripts.css" rel="stylesheet">
@endpush

@push("after-scripts")
    <script>
        $(document).ready(function(){
            $('.btn-delete').on( "click", function() {
            	if (confirm('Are you sure you want to delete the item?')) {
            		window.location = "{!! route('publications.manuscripts.destroyManuscript', ['id'=>$manuscript->id]) !!}";
            	}
            });
            $('.btn-cancel').on('click', function() {
                window.location.href = "{{ route('publications.manuscripts.index') }}";
            })
        });
    </script>
@endpush


    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{ html()->form('POST', route($form_url, ['id'=>$manuscript->id]))
                ->class('form-horizontal form-manuscript')
                ->attribute('id', 'form-' . $manuscript->id)
                ->attribute('enctype', 'multipart/form-data')->open() }}

            <input type="hidden" name="id" value="{{ $manuscript->id }}">
            <input type="hidden" name="category" value="manuscript">
        
            <div class="mt-4">
                {{ html()->label(@('Manuscript Name'))->for('name')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('name', $manuscript->name)->class('block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <!-- div class="mt-4">
                {{ html()->label(@('Category'))->for('category')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('category', ['manuscript'=>'Manuscript'], $manuscript->category)->class('block mt-1 w-full')}}
                </div>
            </div -->

            <div class="mt-4">
                {{ html()->label(@('CSS Class'))->for('view_class')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('view_class', 
                        ['none'=>'Plain', 
                        'background-heighted'=>'Highlight', 
                        'background-red'=>'red', 
                        'background-orange'=>'orange',  
                        'background-greenyellow'=>'greenyellow', 
                        'background-lime'=>'lime', 
                        'background-olive'=>'olive', 
                        'background-cadetblue'=>'cadetblue',
                        'background-aqua'=>'aqua'], 
                        $manuscript->view_class)->class('block mt-1 w-full')}}
                </div><!--col-md-9-->
            </div><!--form-group-->
                    
            <div class="mt-4">
                {{ html()->label(@('Activate Manuscript'))->for('active')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->checkbox('active', empty($manuscript->active) ? 0 : 1, 1)->class('block mt-1') }}
                </div><!--col-md-9-->
            </div><!--form-group-->
            
            <div class="mt-4">
                {{ html()->label(@('Sort Order'))->for('sort')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('sort', $manuscript->sort)->class('block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="block mt-4">
                <div class="col-md-auto">
                @if (!empty($manuscript->id))
                    <x-button type="button" class='btn-delete ms-4'>Delete</x-button>
                @endif
                    <x-button type="button" class='btn-cancel ms-4'>Cancel</x-button>
                    <x-button type="submit" class="btn ms-4">Submit</x-button>
                </div>
            </div><!--form-group-->

        {{ html()->form()->close() }}

    </div><!-- panel body -->

    </div></div></div>
</x-app-layout>
