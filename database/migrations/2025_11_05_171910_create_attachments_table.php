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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachable_type');
            $table->string('attachable_id');
            $table->unsignedBigInteger('size')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('uploaded_by')->nullable();
            $table->timestamps();
            
            $table->index(['attachable_type', 'attachable_id']);
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
