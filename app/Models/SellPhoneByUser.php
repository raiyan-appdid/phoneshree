<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPhoneByUser extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sellPhoneByUserImage()
    {
        return $this->hasMany(SellPhoneByUserImage::class);
    }
}