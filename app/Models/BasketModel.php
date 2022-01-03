<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasketModel extends Model
{
    use HasFactory;
    protected $table = 'tb_basket';

    protected $fillable = [
        'customerId',
        'items',
        'total',
        'discount',
    ];

    public $timestamps = false;

    protected $casts = [
        'customerId' => 'integer',
        'items' => 'array',
        'total' => 'float',
        'discount' => 'array',
    ];

}

