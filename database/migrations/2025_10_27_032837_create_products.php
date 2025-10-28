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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Basic product attributes
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);

            // Image URL
            $table->string('image_url')->nullable();
            // Comparison attributes
            $table->unsignedInteger('watt')->nullable()->comment('Power in watts for comparison settings');
            $table->string('brand')->nullable()->comment('Brand name for comparison settings');

            // Subcategory relation (nullable to avoid ordering issues between migrations).
            // The actual foreign key constraint is added from the subkategori migration when that table exists.
            $table->foreignId('subkategori_product_id')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
