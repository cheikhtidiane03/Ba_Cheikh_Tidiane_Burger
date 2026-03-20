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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
        $table->foreignId('recorded_by')->constrained('users')->onDelete('restrict');
        $table->decimal('amount', 10, 2);
        $table->string('method', 50)->default('cash');
        $table->timestamp('paid_at');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
