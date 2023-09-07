<?php

namespace App\utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    public function upload(UploadedFile $file,string $directory, string $name)
    {

        if ($file) {
            $newFileName = $name . "-" . uniqid() . "." . $file->guessExtension();
            $file->move($directory, $newFileName);

        return $newFileName;
        }
        return false;
    }
}