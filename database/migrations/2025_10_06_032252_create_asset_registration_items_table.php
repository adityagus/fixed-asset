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
        Schema::create('asset_registration_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_registration_id')->constrained('asset_registrations')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 15);
            $table->decimal('total_price', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_registration_items');
    }
};
