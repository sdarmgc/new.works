<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 

class FileUploadController extends Controller
{

    /**
     *  Handle async file upload for file-uploader.blade.php
     */
    public function uploadAsync(Request $request)
    {
        // Name validation matching the dynamic component attribute
        $inputName = $request->has('async_file') ? 'async_file' : 'file_payload';

        $request->validate([
            $inputName => 'required|file|max:1024000', // Backend enforcement (in KB)
        ]);

        if ($request->file($inputName)->isValid()) {
            $path = $request->file($inputName)->store('temp', 'public');
            
            return response()->json([
                'success' => true,
                'path' => $path
            ], 200);
        }

        return response()->json(['error' => 'Invalid file upload.'], 400);
    }

    /**
     *  Handle async file upload for file-uploader.blade.php
     *  Deletes an uploaded temporary/orphaned file from storage.
     */
    public function deleteAsync(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $filePath = $request->input('path');

        // Verify the file exists on the 'public' disk block and prevent directory traversal
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);

            return response()->json([
                'success' => true,
                'message' => 'File successfully deleted from disk.'
            ], 200);
        }

        return response()->json([
            'error' => 'File could not be found or has already been removed.'
        ], 404);
    }
}
