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
        // Tabel untuk transaksi barang masuk (Stock In)
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode transaksi (SI-YYYYMMDD-0001)');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade')->comment('ID supplier');
            $table->date('date')->comment('Tanggal transaksi');
            $table->decimal('total', 15, 2)->default(0)->comment('Total nilai pembelian');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('User yang input');
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('supplier_id');
            $table->index('date');
            $table->index('user_id');
        });

        // Tabel detail item barang masuk
        Schema::create('stock_in_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_in_id')->constrained('stock_ins')->onDelete('cascade')->comment('ID transaksi barang masuk');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('ID produk');
            $table->integer('quantity')->comment('Jumlah barang masuk');
            $table->decimal('price', 15, 2)->comment('Harga satuan');
            $table->decimal('subtotal', 15, 2)->comment('Subtotal (quantity x price)');
            $table->timestamps();

            // Indexes
            $table->index('stock_in_id');
            $table->index('product_id');
        });

        // Tabel untuk transaksi barang keluar (Stock Out)
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode transaksi (SO-YYYYMMDD-0001)');
            $table->string('customer_name')->nullable()->comment('Nama pelanggan');
            $table->date('date')->comment('Tanggal transaksi');
            $table->decimal('total', 15, 2)->default(0)->comment('Total nilai penjualan');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('User yang input');
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('date');
            $table->index('user_id');
        });

        // Tabel detail item barang keluar
        Schema::create('stock_out_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_out_id')->constrained('stock_outs')->onDelete('cascade')->comment('ID transaksi barang keluar');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('ID produk');
            $table->integer('quantity')->comment('Jumlah barang keluar');
            $table->decimal('price', 15, 2)->comment('Harga jual satuan');
            $table->decimal('subtotal', 15, 2)->comment('Subtotal (quantity x price)');
            $table->timestamps();

            // Indexes
            $table->index('stock_out_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dalam urutan terbalik untuk menghindari error foreign key
        Schema::dropIfExists('stock_out_items');
        Schema::dropIfExists('stock_outs');
        Schema::dropIfExists('stock_in_items');
        Schema::dropIfExists('stock_ins');
    }
};
