<?php

namespace Squarelovin\LaravelS3ToUploadedFile;


use finfo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Squarelovin\LaravelS3ToUploadedFile\Exceptions\FileNotFoundException;

class LaravelS3ToUploadedFile extends UploadedFile
{

    /**
     * @throws FileNotFoundException
     */
    public static function createFromDisk(FilesystemAdapter $storage, string $filepath, string $originalName = '',
                                          int               $error = null, bool $test = false): self
    {

        if (!$storage->exists($filepath)) {
            throw new FileNotFoundException("File does not exist at path $filepath.");
        }

        $stream = $storage->get($filepath);

        $tempFile = tempnam(sys_get_temp_dir(), 's3-file-');
        file_put_contents($tempFile, $stream);
        $mimeType = mime_content_type($tempFile);
        return new static($tempFile, $originalName, $mimeType, $error, $test);
    }

    public function isValid(): bool
    {
        return true;
    }
}
