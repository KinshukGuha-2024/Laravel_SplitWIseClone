<?php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileHelper {
    static function upload_file(UploadedFile $file, $path, $file_name) {
        $full_path = "uploads/" . $path . "/" . ($file_name ?? $file->getOriginalName());
        $path = public_path('uploads/' . $path . '/');
        if(!File::exists($path)) {
            \mkdir($path, '0777');
        }

        move_uploaded_file($file->getRealPath(), $full_path);
        return $full_path;
    }

    static function delete_file($file_path) {
        if(File::exists(public_path($file_path))) {
            File::delete(public_path($file_path));
        }
    }
}