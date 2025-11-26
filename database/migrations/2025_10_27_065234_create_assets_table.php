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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_number')->unique();       // key human-friendly
            $table->string('serial_number')->nullable()->unique();
            $table->string('purchase_order_number')->nullable();
            $table->string('register_assets_number')->nullable(); // optional link back
            $table->string('item_name');
            $table->string('category')->nullable();
            $table->string('status')->default('active');    // active, maintenance, retired, lost, etc.
            $table->unsignedBigInteger('assigned_to')->nullable(); // user id
            $table->string('location')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->date('warranty_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
