<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_desc',
        'product_category',
        'product_price',
        'product_code',
        'locale',
    ];
}
