<?php

namespace Squarelovin\LaravelS3ToUploadedFile\Tests;

use Squarelovin\LaravelS3ToUploadedFile\LaravelS3ToUploadedFileServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{


    protected function getPackageProviders($app)
    {
        return [
           LaravelS3ToUploadedFileServiceProvider::class,
        ];
    }

    /**
     * Ignore package discovery from.
     *
     * @return array
     */
    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }
}