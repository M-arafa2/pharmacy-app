<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_banned',
        'pharmacy_id',
        'staff_id',

    ];
    public function staff()
    {
        return $this->belongsTo(staff::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(pharmacy::class);
    }

}
