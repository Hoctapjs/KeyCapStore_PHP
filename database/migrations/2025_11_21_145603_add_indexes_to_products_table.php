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
        Schema::table('products', function (Blueprint $table) {
            $table->index(['status', 'stock'], 'idx_status_stock');
            $table->index('slug', 'idx_slug');
            $table->index('brand_id', 'idx_brand_id');
            $table->index('created_at', 'idx_created_at');
            $table->index('price', 'idx_price');
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'status'], 'idx_product_status');
        });
        
        Schema::table('product_images', function (Blueprint $table) {
            $table->index(['product_id', 'sort_order'], 'idx_product_sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_status_stock');
            $table->dropIndex('idx_slug');
            $table->dropIndex('idx_brand_id');
            $table->dropIndex('idx_created_at');
            $table->dropIndex('idx_price');
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_product_status');
        });
        
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex('idx_product_sort');
        });
    }
};
