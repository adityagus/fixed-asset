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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->string('purchase_request_number')->nullable();
            $table->foreign('purchase_request_number')->references('pr_number')->on('purchase_requests')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('mst_vendor')->onDelete('cascade');
            $table->integer('total_amount');
            $table->enum('status', ['draft', 'waiting approval', 'approved', 'revised', 'rejected'])->default('draft');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            // created_by aja
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
