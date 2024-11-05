<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandleFileTrait 
{
    public static function generateName(UploadedFile $file) {
        $date = Carbon::now()->format('Ymd');
        $fileName =  $date . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $fileName;
    }

    public static function uploadFile(UploadedFile $file, $fileName, $folder = '', $video = false)
    {
        $directory = $video ? 'videos/' : 'images/';

        // Lưu file vào thư mục storage/app/$directory/$folder
        return $file->storeAs($directory . $folder, $fileName);
    }

    public static function removeFile($filename, $folder = '', $video = false)
    {
        $directory = $video ? 'videos/' : 'images/';
        
        // Tạo đường dẫn đầy đủ cho file
        $path = $directory . $folder . '/' . $filename;

        if (Storage::exists($path)) {
            Storage::delete($path);
            return true;
        }

        return false;
    }
}
