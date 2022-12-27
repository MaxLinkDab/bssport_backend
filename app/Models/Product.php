<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function Size_and_price(){
        return $this->hasMany(SizeAndPrice::class);
    }

    public function media(){
        return $this->hasMany(Media::class);
    }

    public function color(){
        return $this->hasMany(Color::class);
    }
}
