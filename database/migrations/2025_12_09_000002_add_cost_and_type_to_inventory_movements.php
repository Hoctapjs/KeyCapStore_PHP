<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Thêm cột unit_cost để lưu giá nhập/đơn vị
            $table->decimal('unit_cost', 15, 2)->nullable()->after('change_qty');
            
            // Thay đổi reason thành type với nhiều loại hơn
            $table->enum('type', ['purchase', 'sale', 'adjustment', 'return', 'manual'])->default('manual')->after('unit_cost');
            
            // Đổi tên reason thành old_reason tạm thời
            $table->renameColumn('reason', 'old_reason');
        });
        
        // Copy dữ liệu từ old_reason sang type
        DB::statement("UPDATE inventory_movements SET type = CASE 
            WHEN old_reason = 'restock' THEN 'purchase'
            WHEN old_reason = 'order' THEN 'sale'
            WHEN old_reason = 'refund' THEN 'return'
            ELSE 'manual'
        END");
        
        // Xóa cột old_reason
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropColumn('old_reason');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Thêm lại cột reason
            $table->enum('reason', ['order', 'restock', 'manual', 'refund'])->default('manual');
            
            // Xóa các cột đã thêm
            $table->dropColumn(['unit_cost', 'type']);
        });
    }
};
