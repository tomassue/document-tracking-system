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
        Schema::create('document_history', function (Blueprint $table) {
            $table->id();
            $table->string('document_id');
            $table->string('status');
            $table->string('user_id');
            $table->string('remarks'); //NOTE - created by, updated by.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_history');
    }
};
