<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_active',
        'image_url',
        'watt',
        'brand',
        'subkategori_product_id',
    ];

    public function subkategori()
    {
        return $this->belongsTo(SubkategoriProduct::class, 'subkategori_product_id');
    }
}
