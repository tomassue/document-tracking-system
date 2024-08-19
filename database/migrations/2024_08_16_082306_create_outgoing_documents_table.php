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
        Schema::create('outgoing_documents', function (Blueprint $table) {
            $table->string('document_no')->primary();
            $table->string('date');
            $table->string('document_details');
            $table->string('person_responsible');
            $table->string('attachments'); //NOTE - FK to file_data table.
            $table->morphs('category'); //NOTE - Polymorphic relationship
            $table->timestamps();
        });

        Schema::create('outgoing_category_procurement', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no');
            $table->string('po_no');
            $table->timestamps();
        });

        Schema::create('outgoing_category_payroll', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_type');
            $table->timestamps();
        });

        Schema::create('outgoing_category_voucher', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_name');
            $table->timestamps();
        });

        Schema::create('outgoing_category_ris', function (Blueprint $table) {
            $table->id();
            $table->string('document_name');
            $table->string('ppmp_code');
            $table->timestamps();
        });

        Schema::create('outgoing_category_others', function (Blueprint $table) {
            $table->id();
            $table->string('document_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_documents');
        Schema::dropIfExists('outgoing_category_procurement');
        Schema::dropIfExists('outgoing_category_payroll');
        Schema::dropIfExists('outgoing_category_voucher');
        Schema::dropIfExists('outgoing_category_ris');
        Schema::dropIfExists('outgoing_category_others');
    }
};
