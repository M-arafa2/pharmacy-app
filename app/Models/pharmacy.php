<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'priority',
        'staff_id',
    ];
    public function area()
    {
        return $this->belongsTo(area::class);
    }
    public function staff()
    {
        return $this->belongsTo(staff::class);
    }
    public function doctor()
    {
        return $this->hasMany(doctor::class);
    }

}
