<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_photo_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->string('current_photo_path')->nullable(); // snapshot of resident's photo at request time
            $table->string('new_photo_path');                 // pending photo path (storage/public/pending-photos/)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('resident_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('reviewed_by')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_photo_requests');
    }
};
