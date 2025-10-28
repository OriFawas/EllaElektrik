<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubkategoriProduct extends Model
{
    use HasFactory;

    protected $table = 'subkategori_products';

    protected $fillable = [
        'kategori_product_id',
        'name',
        'slug',
        'description',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduct::class, 'kategori_product_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'subkategori_product_id');
    }
}
