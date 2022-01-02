<?php

return [
    /**
     * a prefix for the input name to let the package knows that input should be picked up
     */
    'prefix' => 'file-upload',
    /**
     * the default disk to fetch the uploaded file from
     */
    'disk' => env('FILESYSTEM_DRIVER', 'local')
];