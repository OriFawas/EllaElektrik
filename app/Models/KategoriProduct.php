<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduct extends Model
{
    use HasFactory;

    protected $table = 'kategori_products';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function subkategories()
    {
        return $this->hasMany(SubkategoriProduct::class, 'kategori_product_id');
    }
}
