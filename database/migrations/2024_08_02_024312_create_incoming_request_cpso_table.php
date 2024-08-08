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
        Schema::create('incoming_request_cpso', function (Blueprint $table) {
            // $table->id();
            $table->string('incoming_request_id')->primary();
            $table->string('incoming_category');
            $table->string('office_or_barangay_or_organization');
            $table->string('request_date');
            $table->string('category');
            $table->string('venue')->nullable(); //NOTE - IF category is 'venue'.
            $table->time('start_time');
            $table->time('end_time');
            $table->longText('description');
            $table->string('files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_request_cpso');
    }
};
