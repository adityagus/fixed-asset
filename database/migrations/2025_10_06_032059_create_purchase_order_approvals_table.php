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
        Schema::create('purchase_order_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('layer');
            $table->string('request_number');
            $table->foreign('request_number')->references('po_number')->on('purchase_orders')->onDelete('cascade');
            $table->string('approver_by');
            $table->string('email');
            $table->string('jabatan');
            $table->string('type');
            $table->date('approval_date');
            $table->enum('approval_status', ['In Progress', 'Revised', 'Approved', 'Rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_approvals');
    }
};
