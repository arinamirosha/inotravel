<?php

use App\House;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

function storeImage($file)
{
    $path = Storage::disk('public')->putFile('uploads', $file);
    $image = Image::make(Storage::disk('public')->get($path))->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);
    return $path;
}

function updateImage($file, $path)
{
    $image = Image::make($file->getPathname())->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);
}
