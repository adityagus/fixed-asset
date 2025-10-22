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
        Schema::create('asset_registration_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_registration_id')->constrained('asset_registrations')->onDelete('cascade');
            $table->string('approver_by');
            $table->timestamp('approved_at')->nullable();
            $table->enum('approval_status', ['waiting_approval', 'approved', 'revised', 'rejected']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_registration_approvals');
    }
};
