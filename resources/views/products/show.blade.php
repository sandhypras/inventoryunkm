<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('products.edit', $product) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit Produk
                </a>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informasi Produk -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produk</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kode Produk</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $product->code }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Produk</p>
                                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $product->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kategori</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                                            {{ $product->category->icon }} {{ $product->category->name }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Supplier</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $product->supplier->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Harga Beli</p>
                                    <p class="mt-1 text-sm text-gray-900 font-semibold">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Harga Jual</p>
                                    <p class="mt-1 text-sm text-gray-900 font-semibold text-green-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Margin Keuntungan</p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @php
                                            $margin = (($product->selling_price - $product->purchase_price) / $product->purchase_price) * 100;
                                        @endphp
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                            {{ number_format($margin, 1) }}%
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Profit per Unit</p>
                                    <p class="mt-1 text-sm text-gray-900 font-semibold">
                                        Rp {{ number_format($product->selling_price - $product->purchase_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Stok Tersedia</p>
                                    <p class="mt-1 text-sm">
                                        @if($product->stock <= $product->min_stock)
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">
                                                ⚠️ {{ $product->stock }} {{ $product->unit }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                                ✓ {{ $product->stock }} {{ $product->unit }}
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Minimal Stok Alert</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $product->min_stock }} {{ $product->unit }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Satuan</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($product->unit) }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <p class="mt-1 text-sm">
                                        @if($product->status == 'active')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($product->description)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $product->description }}</p>
                                </div>
                            @endif

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-xs text-gray-500">
                                    <div>
                                        <p>Dibuat: {{ $product->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p>Terakhir Update: {{ $product->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gambar & Statistik -->
                <div class="space-y-6">
                    <!-- Gambar Produk -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Produk</h3>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-auto rounded-lg shadow-md">
                            @else
                                <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">Tidak ada gambar</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Nilai Stok -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Nilai Stok</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Nilai Modal:</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Nilai Jual:</span>
                                    <span class="text-sm font-semibold text-green-600">
                                        Rp {{ number_format($product->stock * $product->selling_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">Potensi Profit:</span>
                                        <span class="text-sm font-bold text-green-600">
                                            Rp {{ number_format($product->stock * ($product->selling_price - $product->purchase_price), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('stock-ins.create') }}?product={{ $product->id }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    📦 Tambah Stok
                                </a>
                                <a href="{{ route('stock-outs.create') }}?product={{ $product->id }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    🛒 Catat Penjualan
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        🗑️ Hapus Produk
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
