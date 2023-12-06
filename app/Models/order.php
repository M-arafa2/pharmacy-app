<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = ['is_insured','status','user_id','address_id','pharmacy_id','creator_type','session_id','payment_url','Total_price'];
    public function prescription()
    {
        return $this->hasMany(prescription::class);
    }
    public function medicine()
    {
        return $this->hasMany(medicine::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(address::class);
    }
    public function doctor()
    {
        return $this->hasOne(doctor::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(pharmacy::class);
    }
    public function Revenue()
    {
        return $this->hasOne(Revenue::class);
    }
}
