# Make an AWS s3 uploaded file available to the form request

[comment]: <> ([![Latest Version on Packagist]&#40;https://img.shields.io/packagist/v/squarelovin/laravel-s3-to-uploaded-file.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/squarelovin/laravel-s3-to-uploaded-file&#41;)

[comment]: <> ([![Total Downloads]&#40;https://img.shields.io/packagist/dt/squarelovin/laravel-s3-to-uploaded-file.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/squarelovin/laravel-s3-to-uploaded-file&#41;)

[comment]: <> (![GitHub Actions]&#40;https://github.com/squarelovin/laravel-s3-to-uploaded-file/actions/workflows/main.yml/badge.svg&#41;)

As it is the user normally uploaded to your application, so you can perform apply all the validation rules as usualm

## Installation

You can install the package via composer, add following lines in your composer.json in require section

```json
{
  "require": {
    "squarelovin/laravel-s3-to-uploaded-file": "*"
  }
}
```

and the following in the repo

```json
{
  "repositories": [
    {
      "type": "git",
      "url": "git@github.com:SquarelovinDev/s3-to-uploaded-file.git"
    }
  ]
}
```

Then run

```bash
  composer update 
```

## Usage

Run the following command to publish the config file.

```bash
 php artisan vendor:publish --provider="Squarelovin\LaravelS3ToUploadedFile\LaravelS3ToUploadedFileServiceProvider"
```

Open the `config/s3-upladed.php` config file and set prefix and disk values,

To send an already uploaded s3 file and make it available to the application, you need to send it this format

```json
{
  "file-upload-input-name": {
    "path": "path-to-the-file-on-the-configured-disk",
    "original_name": "original-file-name"
  }
}
```

`file-upload` is a prefix should be set before every file you want to upload, you can change the of it value from the
config file, Then

Add the middleware `Squarelovin\LaravelS3ToUploadedFile\Http\Middleware\S3fileToFileUploadMiddleware`
to your route,

```php
use Squarelovin\LaravelS3ToUploadedFile\Http\Middleware\S3fileToFileUploadMiddleware;

Route::post('my-route','SomeControllerAction')->middleware(S3fileToFileUploadMiddleware::class);
```

And you'll have the file available as any normal file

```php

public function store(Request $request){
    $request->validate([
        "file-upload-input-name" => 'required|image|max:1024'
    ]);
    
    $request->file("file-upload-input-name")->store(
        'files',
        'diskName'
    );
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hazym.aly@gmail.com instead of using the issue tracker.

## Credits

- [Hazem ali](https://github.com/squarelovin)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.