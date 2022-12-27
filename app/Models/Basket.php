<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Sanctum\HasApiTokens;

class Basket extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
/*     public function product(){
        return $this->HasMany(Product::class);
    } */

}
