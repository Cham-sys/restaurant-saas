<?php

use Illuminate\Support\Str;

if (! function_exists('media_url')) {
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/'.ltrim($path, '/'));
    }
}
