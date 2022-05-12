<?php

namespace Squarelovin\LaravelS3ToUploadedFile\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Squarelovin\LaravelS3ToUploadedFile\LaravelS3ToUploadedFile;

class S3fileToFileUploadMiddleware
{
    protected string $prefix;
    protected string $diskName;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->prefix = config('s3-uploaded.prefix', 'file-');
        $this->diskName = config('s3-uploaded.disk', 'local');

        $request = $this->updateFileInputs($request);

        return $next($request);
    }

    public function updateFileInputs(Request $request): Request
    {

        foreach ($request->all() as $name => $value) {

            if (!Str::of($name)->startsWith($this->prefix) || !is_array($value)) {
                continue;
            }

            if (isset($value[0]) && is_array($value[0])) {
                foreach ($value as $key => $item) {
                    $request = $this->setRequest($request, $name, $item, $key);
                }
            } else {
                $request = $this->setRequest($request, $name, $value);
            }


        }

        return $request;
    }

    /**
     * @param Request $request
     * @param $name
     * @param $value
     * @param null $key
     * @return Request
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function setRequest(Request $request, $name, $value, $key = null): Request
    {
        $isArray = !is_null($key);

        $validationPropertyName = $name . ($isArray ? '.*' : null);
        $propertyName = $name . ($isArray ? '.' . $key : null);

        $request->validate([
            $validationPropertyName . ".path" => 'required|string',
            $validationPropertyName . ".original_name" => 'required|string',
        ]);

        $file = LaravelS3ToUploadedFile::createFromDisk(
            Storage::disk($this->diskName), $value['path'], $value['original_name']
        );

        $request->files->set($propertyName, $file);

        if ($isArray) {
            $files = collect($request->get($name, []))->filter(function($value){
                return $value instanceof UploadedFile;
            })->toArray();

            $files[] = $file;
            $request->merge([$name => $files]);
        } else {
            $request->merge([$name => $file]);
        }

        return $request;
    }


}
