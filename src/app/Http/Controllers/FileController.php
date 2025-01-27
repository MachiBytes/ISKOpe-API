<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Upload one or more files to S3 bucket.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:10240', // Adjust MIME types and max size as needed
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('/', $filename, 's3');

            $url = Storage::disk('s3')->url($path);
        }

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => $filename,
            'url' => $url
        ], 200);
    }

    public function uploadMultiple(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,png,pdf,docx|max:10240', // Adjust MIME types and max size as needed
        ]);

        if ($request->hasFile('files')) {
            $uploadedFiles = [];
    
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName();
                    $uploadedFiles[] = $file->storeAs('/uploaded', $fileName, 's3');
                }
            }

            return response()->json([
                'message' => 'Files uploaded successfully.',
                'files' => $uploadedFiles
            ], 200);
        }
        return response()->json(['message' => 'Cannot find files.']);
    }

    public function getAllFiles()
    {
        $images = [];

        // Get all files from a specific folder or the root of the bucket
        $files = Storage::disk('s3')->allFiles('');

        // Filter files to only include images
        foreach ($files as $file) {
            if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                $images[] = $file;
            }
        }

        // Return or handle the list of image files
        return response()->json([
            'images' => $images
        ]);
    }
}
