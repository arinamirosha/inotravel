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

//    $imagePath = $file->store('uploads', 'public');
//    /*resize the image so that the largest side fits within the limit; the smaller
//    side will be scaled to maintain the original aspect ratio*/
//    $image = Image::make(public_path("storage/$imagePath"))
//        ->resize(500, 500, function ($constraint) {
//            $constraint->aspectRatio();
//            $constraint->upsize();
//        });
//    $image->save();
//    return $imagePath;

}

function updateImage($file, $path)
{
    $image = Image::make($file->getPathname())->resize(300, 300)->encode();
    Storage::disk('public')->put($path, $image);
}
