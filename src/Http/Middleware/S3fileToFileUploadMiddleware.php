<?php

namespace Squarelovin\LaravelS3ToUploadedFile\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

            $request->validate([
                $name . ".path" => 'required|string',
                $name . ".original_name" => 'required|string',
            ]);

            $file = LaravelS3ToUploadedFile::createFromDisk(
                Storage::disk($this->diskName), $value['path'], $value['original_name']
            );

//            $name = 'uploaded-' . $name;
            $request->files->set($name, $file);
            $request->merge([$name => $file]);

        }

        return $request;
    }


}
