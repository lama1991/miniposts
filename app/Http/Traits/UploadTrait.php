<?php
namespace App\Http\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile,$path, $disk = 'public')
    {
       
       $image_path = $uploadedFile->store($path,$disk);

        return  $image_path;
    }
    public function uploadMulti(array $uploadedFiles,$path, $disk = 'public')
    {
        $pathes=[];
       
        foreach($uploadedFiles as $file)
         {
            $pathes[]=$file->store($path,$disk);
         }
       return   $pathes;
    }
    public function downloadFile($disk,$path)
    {
        return Storage::disk($disk)->download($path);
    }
}