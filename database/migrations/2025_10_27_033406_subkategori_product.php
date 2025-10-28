<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subkategori_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_product_id')
                ->constrained('kategori_products')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // If products table already exists (it may run earlier), add the foreign key from products.subkategori_product_id -> subkategori_products.id
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'subkategori_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                // only add FK if it does not already exist
                try {
                    $table->foreign('subkategori_product_id')
                        ->references('id')
                        ->on('subkategori_products')
                        ->nullOnDelete();
                } catch (\Exception $e) {
                    // ignore: DB driver might throw if FK already exists or if database doesn't support it at this moment
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key on products if exists
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'subkategori_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                // drop foreign key silently if present
                try {
                    $table->dropForeign(['subkategori_product_id']);
                } catch (\Exception $e) {
                    // ignore
                }
            });
        }

        Schema::dropIfExists('subkategori_products');
    }
};
