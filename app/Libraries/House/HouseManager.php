<?php

namespace App\Libraries\House;

use Illuminate\Support\Facades\Schema;
use Intervention\Image\Facades\Image;

class HouseManager
{
    public function makeTrueArray($req)
    {
        $arr=[];
        if ($req) foreach ($req as $value) $arr[$value]=true;
        return $arr;
    }

    public function makeTrueFalseArray($req, $tableName)
    {
        if (! $req) $req = [];
        $columns = Schema::getColumnListing($tableName);
        $arr=[];
        for ($i = 1; $i < count($columns) - 2; $i++) {
            $col_name = $columns[$i];
            $arr[$col_name] = in_array($col_name, $req) ? true : false;
        }
        return $arr;
    }

    public function storeImage($file)
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
}
