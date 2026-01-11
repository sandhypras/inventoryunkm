<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockOut;
use App\Models\StockOutItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin UMKM',
            'email' => 'admin@umkm.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@umkm.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // 2. Create Categories
        $categories = [
            ['name' => 'Makanan', 'icon' => '🍔', 'description' => 'Produk makanan dan snack'],
            ['name' => 'Minuman', 'icon' => '🥤', 'description' => 'Minuman kemasan dan segar'],
            ['name' => 'Elektronik', 'icon' => '📱', 'description' => 'Peralatan elektronik'],
            ['name' => 'Pakaian', 'icon' => '👕', 'description' => 'Pakaian dan aksesoris'],
            ['name' => 'ATK', 'icon' => '📚', 'description' => 'Alat Tulis Kantor'],
            ['name' => 'Kesehatan', 'icon' => '💊', 'description' => 'Produk kesehatan dan vitamin'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // 3. Create Suppliers
        $suppliers = [
            [
                'name' => 'PT Sumber Rezeki',
                'email' => 'sumberrezeki@supplier.com',
                'phone' => '081234567890',
                'company' => 'PT Sumber Rezeki Makmur',
                'address' => 'Jl. Raya Industri No. 123, Jakarta',
            ],
            [
                'name' => 'CV Maju Jaya',
                'email' => 'majujaya@supplier.com',
                'phone' => '081234567891',
                'company' => 'CV Maju Jaya Sentosa',
                'address' => 'Jl. Perdagangan No. 45, Surabaya',
            ],
            [
                'name' => 'Toko Grosir Makmur',
                'email' => 'grosirmakmur@supplier.com',
                'phone' => '081234567892',
                'company' => 'Toko Grosir Makmur',
                'address' => 'Jl. Pasar Induk No. 78, Bandung',
            ],
            [
                'name' => 'UD Berkah Jaya',
                'email' => 'berkahjaya@supplier.com',
                'phone' => '081234567893',
                'company' => 'UD Berkah Jaya',
                'address' => 'Jl. Niaga No. 90, Medan',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }

        // 4. Create Products
        $products = [
            // Makanan
            ['code' => 'PRD-00001', 'name' => 'Indomie Goreng', 'category_id' => 1, 'supplier_id' => 1, 'purchase_price' => 2500, 'selling_price' => 3500, 'stock' => 0, 'min_stock' => 50, 'unit' => 'pcs'],
            ['code' => 'PRD-00002', 'name' => 'Mie Sedaap Kari', 'category_id' => 1, 'supplier_id' => 1, 'purchase_price' => 2300, 'selling_price' => 3200, 'stock' => 0, 'min_stock' => 50, 'unit' => 'pcs'],
            ['code' => 'PRD-00003', 'name' => 'Chitato Rasa Sapi Panggang', 'category_id' => 1, 'supplier_id' => 1, 'purchase_price' => 8000, 'selling_price' => 11000, 'stock' => 0, 'min_stock' => 20, 'unit' => 'pcs'],
            ['code' => 'PRD-00004', 'name' => 'Tango Wafer Coklat', 'category_id' => 1, 'supplier_id' => 2, 'purchase_price' => 1500, 'selling_price' => 2000, 'stock' => 0, 'min_stock' => 30, 'unit' => 'pcs'],
            ['code' => 'PRD-00005', 'name' => 'Oreo Original', 'category_id' => 1, 'supplier_id' => 2, 'purchase_price' => 9500, 'selling_price' => 13000, 'stock' => 0, 'min_stock' => 15, 'unit' => 'pcs'],

            // Minuman
            ['code' => 'PRD-00006', 'name' => 'Aqua 600ml', 'category_id' => 2, 'supplier_id' => 1, 'purchase_price' => 3000, 'selling_price' => 4000, 'stock' => 0, 'min_stock' => 100, 'unit' => 'pcs'],
            ['code' => 'PRD-00007', 'name' => 'Teh Botol Sosro', 'category_id' => 2, 'supplier_id' => 1, 'purchase_price' => 3500, 'selling_price' => 5000, 'stock' => 0, 'min_stock' => 50, 'unit' => 'pcs'],
            ['code' => 'PRD-00008', 'name' => 'Coca Cola 330ml', 'category_id' => 2, 'supplier_id' => 2, 'purchase_price' => 4000, 'selling_price' => 6000, 'stock' => 0, 'min_stock' => 48, 'unit' => 'pcs'],
            ['code' => 'PRD-00009', 'name' => 'Nutrisari Jeruk (sachet)', 'category_id' => 2, 'supplier_id' => 2, 'purchase_price' => 1000, 'selling_price' => 1500, 'stock' => 0, 'min_stock' => 100, 'unit' => 'pcs'],

            // Elektronik
            ['code' => 'PRD-00010', 'name' => 'Charger Android Type-C', 'category_id' => 3, 'supplier_id' => 3, 'purchase_price' => 25000, 'selling_price' => 35000, 'stock' => 0, 'min_stock' => 10, 'unit' => 'pcs'],
            ['code' => 'PRD-00011', 'name' => 'Headset Gaming RGB', 'category_id' => 3, 'supplier_id' => 3, 'purchase_price' => 150000, 'selling_price' => 200000, 'stock' => 0, 'min_stock' => 5, 'unit' => 'pcs'],
            ['code' => 'PRD-00012', 'name' => 'Mouse Wireless', 'category_id' => 3, 'supplier_id' => 3, 'purchase_price' => 45000, 'selling_price' => 65000, 'stock' => 0, 'min_stock' => 8, 'unit' => 'pcs'],

            // Pakaian
            ['code' => 'PRD-00013', 'name' => 'Kaos Polos Hitam', 'category_id' => 4, 'supplier_id' => 4, 'purchase_price' => 30000, 'selling_price' => 50000, 'stock' => 0, 'min_stock' => 20, 'unit' => 'pcs'],
            ['code' => 'PRD-00014', 'name' => 'Celana Jeans Pria', 'category_id' => 4, 'supplier_id' => 4, 'purchase_price' => 80000, 'selling_price' => 120000, 'stock' => 0, 'min_stock' => 10, 'unit' => 'pcs'],

            // ATK
            ['code' => 'PRD-00015', 'name' => 'Pulpen Pilot', 'category_id' => 5, 'supplier_id' => 3, 'purchase_price' => 2000, 'selling_price' => 3000, 'stock' => 0, 'min_stock' => 50, 'unit' => 'pcs'],
            ['code' => 'PRD-00016', 'name' => 'Buku Tulis 48 Lembar', 'category_id' => 5, 'supplier_id' => 3, 'purchase_price' => 4000, 'selling_price' => 6000, 'stock' => 0, 'min_stock' => 30, 'unit' => 'pcs'],
            ['code' => 'PRD-00017', 'name' => 'Penggaris 30cm', 'category_id' => 5, 'supplier_id' => 3, 'purchase_price' => 3000, 'selling_price' => 5000, 'stock' => 0, 'min_stock' => 20, 'unit' => 'pcs'],

            // Kesehatan
            ['code' => 'PRD-00018', 'name' => 'Masker KN95', 'category_id' => 6, 'supplier_id' => 2, 'purchase_price' => 5000, 'selling_price' => 8000, 'stock' => 0, 'min_stock' => 100, 'unit' => 'pcs'],
            ['code' => 'PRD-00019', 'name' => 'Hand Sanitizer 100ml', 'category_id' => 6, 'supplier_id' => 2, 'purchase_price' => 10000, 'selling_price' => 15000, 'stock' => 0, 'min_stock' => 50, 'unit' => 'pcs'],
            ['code' => 'PRD-00020', 'name' => 'Vitamin C 1000mg', 'category_id' => 6, 'supplier_id' => 2, 'purchase_price' => 25000, 'selling_price' => 35000, 'stock' => 0, 'min_stock' => 20, 'unit' => 'pcs'],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }

        // 5. Create Stock In Transactions (Pembelian) - 15 transaksi dalam 2 bulan terakhir
        $stockInData = [
            // Minggu 1 - 2 bulan lalu
            ['date' => now()->subDays(60), 'supplier_id' => 1, 'items' => [[1, 200, 2500], [2, 150, 2300], [6, 300, 3000], [7, 200, 3500]]],
            ['date' => now()->subDays(58), 'supplier_id' => 2, 'items' => [[3, 100, 8000], [4, 150, 1500], [8, 200, 4000], [9, 300, 1000]]],

            // Minggu 2
            ['date' => now()->subDays(52), 'supplier_id' => 3, 'items' => [[10, 50, 25000], [11, 20, 150000], [12, 30, 45000]]],
            ['date' => now()->subDays(50), 'supplier_id' => 4, 'items' => [[13, 80, 30000], [14, 40, 80000]]],

            // Minggu 3
            ['date' => now()->subDays(45), 'supplier_id' => 1, 'items' => [[1, 150, 2500], [2, 100, 2300], [6, 200, 3000]]],
            ['date' => now()->subDays(43), 'supplier_id' => 2, 'items' => [[5, 60, 9500], [18, 200, 5000], [19, 100, 10000]]],

            // Minggu 4 - 1 bulan lalu
            ['date' => now()->subDays(38), 'supplier_id' => 3, 'items' => [[15, 200, 2000], [16, 150, 4000], [17, 100, 3000]]],
            ['date' => now()->subDays(35), 'supplier_id' => 1, 'items' => [[7, 150, 3500], [8, 100, 4000]]],

            // Minggu 5
            ['date' => now()->subDays(30), 'supplier_id' => 2, 'items' => [[20, 80, 25000], [3, 80, 8000], [4, 100, 1500]]],
            ['date' => now()->subDays(28), 'supplier_id' => 4, 'items' => [[13, 60, 30000], [14, 30, 80000]]],

            // Minggu 6
            ['date' => now()->subDays(22), 'supplier_id' => 1, 'items' => [[1, 100, 2500], [6, 150, 3000]]],
            ['date' => now()->subDays(20), 'supplier_id' => 3, 'items' => [[10, 30, 25000], [11, 15, 150000], [12, 25, 45000]]],

            // Minggu terakhir (recent)
            ['date' => now()->subDays(15), 'supplier_id' => 2, 'items' => [[9, 200, 1000], [18, 150, 5000], [19, 80, 10000]]],
            ['date' => now()->subDays(10), 'supplier_id' => 1, 'items' => [[1, 120, 2500], [2, 100, 2300], [7, 100, 3500]]],
            ['date' => now()->subDays(5), 'supplier_id' => 3, 'items' => [[15, 150, 2000], [16, 100, 4000]]],
        ];

        foreach ($stockInData as $index => $data) {
            $number = $index + 1;
            $code = 'SI-' . $data['date']->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $total = 0;
            foreach ($data['items'] as $item) {
                $total += $item[1] * $item[2]; // qty * price
            }

            $stockIn = StockIn::create([
                'code' => $code,
                'supplier_id' => $data['supplier_id'],
                'date' => $data['date'],
                'total' => $total,
                'notes' => 'Pembelian stock ' . $data['date']->format('d M Y'),
                'user_id' => $admin->id,
            ]);

            foreach ($data['items'] as $item) {
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item[0],
                    'quantity' => $item[1],
                    'price' => $item[2],
                    'subtotal' => $item[1] * $item[2],
                ]);

                // Update product stock
                Product::find($item[0])->increment('stock', $item[1]);
            }
        }

        // 6. Create Stock Out Transactions (Penjualan) - 25 transaksi dalam 2 bulan
        $customers = ['Budi Santoso', 'Ani Wijaya', 'Joko Susilo', 'Dewi Lestari', 'Ahmad Ridwan', null, null, null]; // null = umum

        $stockOutData = [
            // Generate various sales transactions
            ['date' => now()->subDays(59), 'items' => [[1, 20], [6, 30], [7, 15]]],
            ['date' => now()->subDays(58), 'items' => [[3, 10], [8, 20]]],
            ['date' => now()->subDays(56), 'items' => [[13, 5], [14, 3]]],
            ['date' => now()->subDays(55), 'items' => [[1, 15], [2, 10], [6, 25]]],
            ['date' => now()->subDays(53), 'items' => [[10, 2], [12, 3]]],

            ['date' => now()->subDays(50), 'items' => [[4, 20], [5, 8], [9, 40]]],
            ['date' => now()->subDays(48), 'items' => [[15, 30], [16, 25], [17, 15]]],
            ['date' => now()->subDays(47), 'items' => [[18, 25], [19, 15]]],
            ['date' => now()->subDays(45), 'items' => [[1, 25], [7, 20], [8, 15]]],
            ['date' => now()->subDays(43), 'items' => [[11, 2], [12, 4]]],

            ['date' => now()->subDays(40), 'items' => [[3, 15], [4, 25], [5, 10]]],
            ['date' => now()->subDays(38), 'items' => [[20, 10], [19, 12]]],
            ['date' => now()->subDays(35), 'items' => [[1, 30], [2, 20], [6, 40]]],
            ['date' => now()->subDays(33), 'items' => [[13, 10], [14, 5]]],
            ['date' => now()->subDays(30), 'items' => [[7, 25], [8, 30], [9, 50]]],

            ['date' => now()->subDays(28), 'items' => [[10, 5], [11, 3]]],
            ['date' => now()->subDays(25), 'items' => [[15, 40], [16, 30]]],
            ['date' => now()->subDays(23), 'items' => [[1, 18], [6, 22]]],
            ['date' => now()->subDays(20), 'items' => [[3, 12], [8, 18]]],
            ['date' => now()->subDays(18), 'items' => [[18, 30], [19, 20], [20, 8]]],

            ['date' => now()->subDays(15), 'items' => [[1, 22], [2, 18], [7, 20]]],
            ['date' => now()->subDays(12), 'items' => [[4, 30], [5, 12]]],
            ['date' => now()->subDays(10), 'items' => [[13, 8], [14, 4]]],
            ['date' => now()->subDays(7), 'items' => [[6, 35], [7, 28], [8, 20]]],
            ['date' => now()->subDays(3), 'items' => [[1, 15], [3, 8], [10, 3]]],
        ];

        foreach ($stockOutData as $index => $data) {
            $number = $index + 1;
            $code = 'SO-' . $data['date']->format('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::find($item[0]);
                $total += $item[1] * $product->selling_price;
            }

            $stockOut = StockOut::create([
                'code' => $code,
                'customer_name' => $customers[array_rand($customers)],
                'date' => $data['date'],
                'total' => $total,
                'notes' => 'Penjualan ' . $data['date']->format('d M Y'),
                'user_id' => rand(0, 1) ? $admin->id : $user->id,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::find($item[0]);

                StockOutItem::create([
                    'stock_out_id' => $stockOut->id,
                    'product_id' => $item[0],
                    'quantity' => $item[1],
                    'price' => $product->selling_price,
                    'subtotal' => $item[1] * $product->selling_price,
                ]);

                // Update product stock
                $product->decrement('stock', $item[1]);
            }
        }

        echo "\n✅ Database seeding completed successfully!\n";
        echo "📊 Data yang dibuat:\n";
        echo "   - 2 Users (admin@umkm.com & kasir@umkm.com - password: password)\n";
        echo "   - 6 Categories\n";
        echo "   - 4 Suppliers\n";
        echo "   - 20 Products\n";
        echo "   - 15 Stock In Transactions (Pembelian)\n";
        echo "   - 25 Stock Out Transactions (Penjualan)\n";
        echo "\n🔐 Login Info:\n";
        echo "   Email: admin@umkm.com\n";
        echo "   Password: password\n\n";
    }
}
