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
 *
 * @return mixed
 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
 */
function storeImage($file)
{
    $path  = Storage::disk('public')->putFile('uploads', $file);
    $image = Image::make(Storage::disk('public')->get($path))->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);

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
