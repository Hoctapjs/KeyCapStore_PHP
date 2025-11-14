<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique()->nullable();
            $table->string('title');
            $table->string('code')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->json('colors')->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->json('specs')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'active', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
