<?php

use App\Booking;
use App\House;
use App\TemporaryImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Store image and return it's path
 *
 * @param $file
 * @param bool $isSquare
 *
 * @return mixed
 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
 */
function storeImage($file, $isSquare = true)
{
    $path = Storage::disk('public')->putFile('uploads', $file);
    $image  = Image::make(Storage::disk('public')->get($path));
    $image = $image->resize(400, 400, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    if ($isSquare) {
        $image = $image->crop(200, 200);
    }

    Storage::disk('public')->put($path, $image->encode());

    return $path;
}

/**
 * Get image path from temporary_images table and then delete
 *
 * @param $id
 *
 * @return mixed
 */
function getImgFromTempById($id)
{
    $img  = TemporaryImage::find($id);
    $path = $img->image;
    $img->delete();

    return $path;
}
