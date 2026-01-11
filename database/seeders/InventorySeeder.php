<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockOut;
use App\Models\StockOutItem;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. SETTINGS (Email Owner)
        Setting::create(['key' => 'owner_email', 'value' => 'owner@umkm.com', 'type' => 'email', 'group' => 'email']);
        Setting::create(['key' => 'owner_name', 'value' => 'Owner UMKM', 'type' => 'text', 'group' => 'email']);
        Setting::create(['key' => 'company_name', 'value' => 'Toko Sumber Rezeki', 'type' => 'text', 'group' => 'general']);

        // 2. KATEGORI (10 categories)
        $categories = [
            ['name' => '📱 Electronics', 'emoji' => '📱', 'description' => 'Gadget & aksesoris'],
            ['name' => '👕 Fashion', 'emoji' => '👕', 'description' => 'Pakaian & aksesoris'],
            ['name' => '🍚 Makanan Pokok', 'emoji' => '🍚', 'description' => 'Beras, minyak, gula'],
            ['name' => '🥤 Minuman', 'emoji' => '🥤', 'description' => 'Teh, kopi, soda'],
            ['name' => '🍎 Buah & Sayur', 'emoji' => '🍎', 'description' => 'Segar harian'],
            ['name' => '🧴 Kebutuhan Harian', 'emoji' => '🧴', 'description' => 'Sabun, pasta gigi'],
            ['name' => '📚 Alat Tulis', 'emoji' => '📚', 'description' => 'Buku & pulpen'],
            ['name' => '🏠 Rumah Tangga', 'emoji' => '🏠', 'description' => 'Peralatan dapur'],
            ['name' => '🚗 Otomotif', 'emoji' => '🚗', 'description' => 'Oli & aksesoris motor'],
            ['name' => '💊 Obat-obatan', 'emoji' => '💊', 'description' => 'Vitamin & obat ringan'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // 3. SUPPLIER (8 suppliers)
        $suppliers = [
            ['name' => 'PT Elektronik Jaya', 'email' => 'elektronik@jaya.com', 'phone' => '081234567890', 'address' => 'Jl. Sudirman No.1, Jakarta', 'company' => 'Elektronik Distributor'],
            ['name' => 'Budi Fashion', 'email' => 'budi@fashion.id', 'phone' => '081234567891', 'address' => 'Jl. Thamrin, Bandung', 'company' => 'Fashion UMKM'],
            ['name' => 'Sumber Pangan Sejahtera', 'email' => 'pangan@umkm.com', 'phone' => '081234567892', 'address' => 'Pasar Minggu, Jakarta Selatan', 'company' => 'Sembako Grosir'],
            ['name' => 'Minuman Segar Abadi', 'email' => 'minuman@abadi.co.id', 'phone' => '081234567893', 'address' => 'Jl. Gatot Subroto, Surabaya', 'company' => 'Beverage Supplier'],
            ['name' => 'Tani Makmur', 'email' => 'tani@makmur.com', 'phone' => '081234567894', 'address' => 'Desa Sukamaju, Bogor', 'company' => 'Agribisnis'],
            ['name' => 'Harian Prima', 'email' => 'harian@prima.id', 'phone' => '081234567895', 'address' => 'Mall ABC, Depok', 'company' => 'Daily Needs'],
            ['name' => 'Otomotif Racer', 'email' => 'racer@otomotif.com', 'phone' => '081234567896', 'address' => 'Jl. Raya Bogor Km10', 'company' => 'Auto Parts'],
            ['name' => 'Apotek Sehat', 'email' => 'sehat@apotek.id', 'phone' => '081234567897', 'address' => 'Jl. Palmerah, Jakarta Barat', 'company' => 'Pharmacy'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }

        // 4. USER (5 users dengan role)
        $admin = User::create([
            'name' => 'Admin Owner',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $gudang = User::create([
            'name' => 'Staff Gudang',
            'email' => 'gudang@inventory.com',
            'password' => Hash::make('password'),
        ]);
        $gudang->assignRole('gudang');

        $kasir = User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@inventory.com',
            'password' => Hash::make('password'),
        ]);
        $kasir->assignRole('kasir');

        $viewer = User::create([
            'name' => 'Auditor',
            'email' => 'viewer@inventory.com',
            'password' => Hash::make('password'),
        ]);
        $viewer->assignRole('viewer');

        $manager = User::create([
            'name' => 'Manager Operasional',
            'email' => 'manager@inventory.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('manager'); // Asumsi role manager sudah dibuat di RolePermissionSeeder

        // 5. PRODUK (50 products)
        $productsData = [
            // Electronics
            ['code' => 'PRD-001', 'name' => 'HP Samsung A15', 'category_id' => 1, 'supplier_id' => 1, 'purchase_price' => 2000000, 'selling_price' => 2500000, 'stock' => 15, 'min_stock' => 5, 'unit' => 'unit', 'image' => 'https://source.unsplash.com/300x300/?phone', 'status' => 'active'],
            ['code' => 'PRD-002', 'name' => 'Charger Type C', 'category_id' => 1, 'supplier_id' => 1, 'purchase_price' => 50000, 'selling_price' => 75000, 'stock' => 3, 'min_stock' => 10, 'unit' => 'pcs', 'image' => 'https://source.unsplash.com/300x300/?charger', 'status' => 'active'],
            // Fashion
            ['code' => 'PRD-003', 'name' => 'Kaos Polos Cotton', 'category_id' => 2, 'supplier_id' => 2, 'purchase_price' => 50000, 'selling_price' => 80000, 'stock' => 50, 'min_stock' => 20, 'unit' => 'pcs', 'image' => 'https://source.unsplash.com/300x300/?tshirt', 'status' => 'active'],
            // Makanan Pokok
            ['code' => 'PRD-004', 'name' => 'Beras 5kg Premium', 'category_id' => 3, 'supplier_id' => 3, 'purchase_price' => 75000, 'selling_price' => 95000, 'stock' => 100, 'min_stock' => 20, 'unit' => 'karung', 'image' => 'https://source.unsplash.com/300x300/?rice', 'status' => 'active'],
            // ... (Tambahkan 46 produk lagi dengan variasi serupa untuk total 50)
            // Contoh tambahan (copy pattern):
            // ['code' => 'PRD-005', 'name' => 'Minyak Goreng 1L', 'category_id' => 3, 'supplier_id' => 3, ...],
        ];
        foreach ($productsData as $prod) { // Extend array ini untuk 50 items
            Product::create($prod);
        }

        // 6. STOCK IN (20 transaksi masuk)
        for ($i = 1; $i <= 20; $i++) {
            $stockIn = StockIn::create([
                'code' => 'SI-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'supplier_id' => rand(1, 8),
                'date' => now()->subDays(rand(1, 30)),
                'total' => 0,
                'notes' => 'Pembelian rutin ' . $i,
                'user_id' => $gudang->id,
            ]);

            // 2-5 items per transaksi
            $itemsCount = rand(2, 5);
            $total = 0;
            for ($j = 0; $j < $itemsCount; $j++) {
                $product = Product::inRandomOrder()->first();
                $qty = rand(5, 20);
                $subtotal = $qty * $product->purchase_price;
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->purchase_price,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
                // Update stok produk
                $product->increment('stock', $qty);
            }
            $stockIn->update(['total' => $total]);
        }

        // 7. STOCK OUT (15 transaksi keluar)
        for ($i = 1; $i <= 15; $i++) {
            $stockOut = StockOut::create([
                'code' => 'SO-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_name' => 'Pelanggan ' . $i,
                'date' => now()->subDays(rand(0, 10)),
                'total' => 0,
                'notes' => 'Penjualan harian ' . $i,
                'user_id' => $kasir->id,
            ]);

            // 1-3 items per transaksi
            $itemsCount = rand(1, 3);
            $total = 0;
            for ($j = 0; $j < $itemsCount; $j++) {
                $product = Product::where('stock', '>', 0)->inRandomOrder()->first();
                if ($product) {
                    $qty = rand(1, min(5, $product->stock));
                    $subtotal = $qty * $product->selling_price;
                    StockOutItem::create([
                        'stock_out_id' => $stockOut->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->selling_price,
                        'subtotal' => $subtotal,
                    ]);
                    $total += $subtotal;
                    // Kurangi stok produk
                    $product->decrement('stock', $qty);
                }
            }
            $stockOut->update(['total' => $total]);
        }

        echo "✅ Seeder selesai! Data siap untuk testing.\n";
        echo "👑 Login Test:\n";
        echo "- Admin: admin@inventory.com / password\n";
        echo "- Gudang: gudang@inventory.com / password\n";
        echo "- Kasir: kasir@inventory.com / password\n";
    }
}
