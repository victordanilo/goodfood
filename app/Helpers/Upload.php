<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Upload
{
    public static function img(UploadedFile $file, String $path)
    {
        $fileName = RamseyUuid::uuid4().'-'.date('Ymdhis');
        $fileName = "{$fileName}.{$file->getClientOriginalExtension()}";

        $filePath = storage_path("app/public/{$path}");

        $file->move($filePath, $fileName);

        return $fileName;
    }
}
