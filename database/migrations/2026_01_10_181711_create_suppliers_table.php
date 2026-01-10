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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama supplier');
            $table->string('email')->nullable()->unique()->comment('Email supplier');
            $table->string('phone')->comment('Nomor telepon');
            $table->text('address')->nullable()->comment('Alamat lengkap');
            $table->string('company')->nullable()->comment('Nama perusahaan');
            $table->timestamps();

            // Indexes untuk performa query
            $table->index('name');
            $table->index('email');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
