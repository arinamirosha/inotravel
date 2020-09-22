<?php

namespace App\Libraries\House;

use Illuminate\Support\Facades\Schema;

class HouseManager
{
    /**
     * Attach facilities and restrictions to the house
     *
     * @param $req_fac
     * @param $req_rest
     * @param $house
     */
    public function attachToHouse($req_fac, $req_rest, $house)
    {
        if ($req_fac) {
            foreach ($req_fac as $facility) {
                $house->facilities()->attach($facility);
            }
        }

        if ($req_rest) {
            foreach ($req_rest as $restriction) {
                $house->restrictions()->attach($restriction);
            }
        }
    }
}
