<?php

use Intervention\Image\Facades\Image;

function storeImage($file)
{
    $imagePath = $file->store('uploads', 'public');
    /*resize the image so that the largest side fits within the limit; the smaller
    side will be scaled to maintain the original aspect ratio*/
    $image = Image::make(public_path("storage/$imagePath"))
        ->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    $image->save();
    return $imagePath;
}
