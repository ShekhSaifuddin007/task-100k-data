<?php

namespace App\Helpers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

trait InteractsWithFile
{
    protected UploadedFile $file;

    public function file(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function save($destination = '/'): string
    {
        return $this->file->store($destination);
    }

    public function pathToUploadedFile($path, $test = true): UploadedFile
    {
        $filesystem = new Filesystem;

        $name = $filesystem->name($path);
        $extension = $filesystem->extension($path);
        $originalName = $name . '.' . $extension;
        $mimeType = $filesystem->mimeType($path);
        $error = null;

        return new UploadedFile($path, $originalName, $mimeType, $error, $test);
    }

    public function move($to)
    {
        copy($this->file->getRealPath(),   $to . DIRECTORY_SEPARATOR . $this->file->getFilename());
        unlink($this->file->getRealPath());
    }
}
