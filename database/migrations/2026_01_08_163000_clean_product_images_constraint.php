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
        Schema::table('product_images', function (Blueprint $table) {
            // Remove the duplicate constraint
            try {
                $table->dropForeign(['product_id']);
            } catch (\Exception $e) {
                // Already dropped, continue
            }
        });

        Schema::table('product_images', function (Blueprint $table) {
            // Re-create with the original constraint name
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
