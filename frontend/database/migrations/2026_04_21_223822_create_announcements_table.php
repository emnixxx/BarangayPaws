<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('title', 200);
            $table->string('category');
            $table->string('target_pet_type');
            $table->date('event_date')->nullable();
            $table->string('location', 255);
            $table->text('details');
            $table->unsignedBigInteger('posted_by');
            $table->foreign('posted_by')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
