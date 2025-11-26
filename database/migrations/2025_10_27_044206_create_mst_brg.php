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
        Schema::create('mst_brg', function (Blueprint $table) {
            $table->id();
            $table->string('nama_brg');
            $table->foreignId('id_katbrg')->constrained('mst_katbrg')->onDelete('cascade');
            $table->foreignId('id_tipebrg')->constrained('mst_tipebrg')->onDelete('cascade');
            $table->foreignId('id_merkbrg')->constrained('mst_merkbrg')->onDelete('cascade');
            $table->string('ket_brg')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_brg');
    }
};
