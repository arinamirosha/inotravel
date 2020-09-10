<?php

namespace App\Libraries\House;

use Illuminate\Support\Facades\Schema;

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

}
