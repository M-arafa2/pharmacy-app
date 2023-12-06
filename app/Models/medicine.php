<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicine extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','name','quantity','price','type'];
    public function order()
    {
        return $this->belongsTo(order::class);
    }
}
