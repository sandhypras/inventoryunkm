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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode produk unik');
            $table->string('name')->comment('Nama produk');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->comment('ID kategori');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade')->comment('ID supplier');
            $table->text('description')->nullable()->comment('Deskripsi produk');
            $table->decimal('purchase_price', 15, 2)->comment('Harga beli/modal');
            $table->decimal('selling_price', 15, 2)->comment('Harga jual');
            $table->integer('stock')->default(0)->comment('Stok tersedia');
            $table->integer('min_stock')->default(5)->comment('Minimal stok (untuk alert)');
            $table->string('unit')->default('pcs')->comment('Satuan (pcs, kg, liter, dll)');
            $table->string('image')->nullable()->comment('Path gambar produk');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Status produk');
            $table->timestamps();

            // Indexes untuk performa query
            $table->index('code');
            $table->index('name');
            $table->index('category_id');
            $table->index('supplier_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
