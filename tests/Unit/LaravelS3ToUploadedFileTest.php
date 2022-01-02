<?php

namespace Squarelovin\LaravelS3ToUploadedFile\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Squarelovin\LaravelS3ToUploadedFile\Http\Middleware\S3fileToFileUploadMiddleware;
use Squarelovin\LaravelS3ToUploadedFile\Tests\TestCase;

class LaravelS3ToUploadedFileTest extends TestCase
{

    /** @test */
    public function it_adds_the_file_to_the_request()
    {
        Storage::fake();

        $request = new Request();

        $prefix = config('s3-uploaded.prefix');
        $inputName = $prefix . '-0';

        $request->headers->set('Authorization', 'Bearer some-token');

        $filename = 'some-file.png';

        $fakeFile = Storage::put($filename, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=='
        ));


        $request->merge([$inputName => [
            'path' => $filename,
            'original_name' => $filename
        ]]);

        (new S3fileToFileUploadMiddleware())->handle($request, function ($request) {
            $this->assertNotEmpty($request->files);
        });


    }

    /** @test */
    public function it_makes_the_file_available_for_validation()
    {
        Storage::fake();

        $request = new Request();

        $prefix = config('s3-uploaded.prefix');
        $inputName = $prefix . '-0';

        $request->headers->set('Authorization', 'Bearer some-token');

        $filename = 'some-file.png';

        $fakeFile = Storage::put($filename, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+P+/HgAFhAJ/wlseKgAAAABJRU5ErkJggg=='
        ));


        $request->merge([$inputName => [
            'path' => $filename,
            'original_name' => $filename
        ]]);

        (new S3fileToFileUploadMiddleware())->handle($request, function ($request) use ($inputName) {

            $validator = Validator::make($request->all(),
                [
                    $inputName => 'required|image'
                ]);

            $this->assertFalse($validator->fails());

        });
    }

    /** @test */
    public function it_requires_a_path()
    {

        $request = new Request();
        $prefix = config('s3-uploaded.prefix');
        $inputName = $prefix . '-0';
        $request->merge([$inputName => []]);

        try {
            (new S3fileToFileUploadMiddleware())->handle($request, function ($request) {
            });
            $this->assertTrue(false, 'a validation error should be thrown');
        } catch (ValidationException $exception) {
            $this->assertNotEmpty($exception->errors());
            $this->assertArrayHasKey($inputName . '.path', $exception->errors());
        }

    }


    /** @test */
    public function it_requires_an_original_name()
    {

        $request = new Request();

        $prefix = config('s3-uploaded.prefix');
        $inputName = $prefix . '-0';

        $request->merge([$inputName => [
            'path' => 'string'
        ]]);

        try {
            (new S3fileToFileUploadMiddleware())->handle($request, function ($request) {
            });
            $this->assertTrue(false, 'a validation error should be thrown');
        } catch (ValidationException $exception) {
            $this->assertNotEmpty($exception->errors());
            $this->assertArrayHasKey($inputName . '.original_name', $exception->errors());
        }

    }

}