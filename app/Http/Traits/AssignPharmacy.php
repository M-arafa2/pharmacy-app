<?php

namespace App\Http\Traits;

use App\Models\pharmacy;

trait AssignPharmacy
{
    public function Assign(int $area_id)
    {

        $highestpriority = pharmacy::where('area_id', $area_id)->max('priority');
        $pharmacy = pharmacy::where('priority', $highestpriority)->first();
        if(empty($pharmacy)) {
            $highestpriority = pharmacy::max('priority');
            $pharmacy = pharmacy::where('priority', $highestpriority)->first();
        }
        return $pharmacy->id;
    }
}
