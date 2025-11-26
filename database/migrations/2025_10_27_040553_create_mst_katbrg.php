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
        Schema::create('mst_katbrg', function (Blueprint $table) {
            $table->id();
            $table->string('nama_katbrg');
            $table->integer('umur');
            $table->boolean('status')->default(true);
            $table->integer('coa1')->nullable();
            $table->integer('coa2')->nullable();
            $table->integer('coa3')->nullable();
            $table->integer('coa4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_katbrg');
    }
};
