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
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_request_number'); // tambahkan ini
            $table->foreign('purchase_request_number')->references('pr_number')->on('purchase_requests')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity');
            $table->integer('unit_price', 15);
            $table->integer('total_price', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
    }
};
