<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        // [QUAN TRỌNG] Liên kết với bảng users
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        
        $table->string('receiver_name');
        $table->string('receiver_phone');
        $table->string('receiver_address');
        $table->text('note')->nullable();
        $table->decimal('total_price', 12, 2);
        $table->string('status')->default('pending'); // pending, processing, completed, cancelled
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
