<?php

namespace Squarelovin\LaravelS3ToUploadedFile;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Squarelovin\LaravelS3ToUploadedFile\Skeleton\SkeletonClass
 */
class LaravelS3ToUploadedFileFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-s3-to-uploaded-file';
    }
}
