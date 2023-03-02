<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPhoneByUserImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sellPhoneByUser()
    {
        return $this->belongsTo(SellPhoneByUser::class);
    }
}