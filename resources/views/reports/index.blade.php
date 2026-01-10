<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pilih Jenis Laporan</h3>
                    <p class="text-sm text-gray-600 mb-6">Silakan pilih jenis laporan yang ingin Anda lihat</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Laporan Stok -->
                <a href="{{ route('reports.stock') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-4 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Stok</h3>
                            <p class="text-sm text-gray-600">Lihat laporan stok produk, nilai stok, dan produk dengan stok menipis</p>
                        </div>
                    </div>
                </a>

                <!-- Laporan Penjualan -->
                <a href="{{ route('reports.sales') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mb-4 group-hover:bg-green-200 transition-colors">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Penjualan</h3>
                            <p class="text-sm text-gray-600">Lihat laporan penjualan, transaksi, dan produk terlaris</p>
                        </div>
                    </div>
                </a>

                <!-- Laporan Pembelian -->
                <a href="{{ route('reports.purchases') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-lg mb-4 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Pembelian</h3>
                            <p class="text-sm text-gray-600">Lihat laporan pembelian dari supplier dan produk yang sering dibeli</p>
                        </div>
                    </div>
                </a>

                <!-- Laporan Profit -->
                <a href="{{ route('reports.profit') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-lg mb-4 group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Profit</h3>
                            <p class="text-sm text-gray-600">Analisa profit, margin keuntungan, dan performa penjualan</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Cepat</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Product::count() }}</p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Transaksi Bulan Ini</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">
                                {{ \App\Models\StockOut::whereMonth('date', now()->month)->count() }}
                            </p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Pembelian Bulan Ini</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">
                                {{ \App\Models\StockIn::whereMonth('date', now()->month)->count() }}
                            </p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Stok Menipis</p>
                            <p class="text-2xl font-bold text-red-600 mt-1">
                                {{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Tips Penggunaan Laporan</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Gunakan filter tanggal untuk melihat laporan periode tertentu</li>
                                <li>Export laporan ke Excel atau PDF untuk arsip</li>
                                <li>Cek laporan stok secara rutin untuk menghindari kehabisan barang</li>
                                <li>Analisa laporan profit untuk mengetahui produk paling menguntungkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
