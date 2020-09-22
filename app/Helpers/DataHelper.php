<?php

use App\House;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Store image and return it's path
 *
 * @param $file
 * @return mixed
 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
 */
function storeImage($file)
{
    $path = Storage::disk('public')->putFile('uploads', $file);
    $image = Image::make(Storage::disk('public')->get($path))->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);

    return $path;
}

/**
 * Replace existing image
 *
 * @param $file
 * @param $path
 */
function updateImage($file, $path)
{
    $image = Image::make($file->getPathname())->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);
}
