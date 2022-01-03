<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'tb_products';

    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
    ];

    public $timestamps = false;

    protected $casts = [
        'name' => 'string',
        'category' => 'integer',
        'price' => 'float',
        'stock' => 'integer',
    ];

}

