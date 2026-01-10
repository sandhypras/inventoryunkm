<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Penjualan') }}
            </h2>
            <a href="{{ route('stock-outs.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Header Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kode Transaksi</p>
                                    <p class="mt-1 text-lg font-bold text-gray-900">{{ $stockOut->code }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tanggal</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($stockOut->date)->format('d F Y') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Customer</p>
                                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $stockOut->customer_name ?: 'Umum' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500">Diinput Oleh</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $stockOut->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $stockOut->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($stockOut->notes)
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-500">Catatan</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $stockOut->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Item</h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            $totalModal = 0;
                                        @endphp
                                        @foreach($stockOut->items as $index => $item)
                                            @php
                                                $modal = $item->quantity * $item->product->purchase_price;
                                                $totalModal += $modal;
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $item->product->code }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                                    {{ number_format($item->quantity, 0) }} {{ $item->product->unit }}
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-gray-900">
                                                TOTAL PENJUALAN:
                                            </td>
                                            <td class="px-4 py-3 text-right text-lg font-bold text-green-600">
                                                Rp {{ number_format($stockOut->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr class="bg-blue-50">
                                            <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                                Total Modal:
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-blue-700">
                                                Rp {{ number_format($totalModal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr class="bg-green-50">
                                            <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-gray-900">
                                                PROFIT:
                                            </td>
                                            <td class="px-4 py-3 text-right text-lg font-bold text-green-600">
                                                Rp {{ number_format($stockOut->total - $totalModal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Summary Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Total Item:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $stockOut->items->count() }} item</span>
                                </div>

                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Total Quantity:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $stockOut->items->sum('quantity') }} unit</span>
                                </div>

                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Total Modal:</span>
                                    <span class="text-sm font-semibold text-blue-600">Rp {{ number_format($totalModal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Total Penjualan:</span>
                                    <span class="text-sm font-semibold text-green-600">Rp {{ number_format($stockOut->total, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center pt-3 bg-green-50 -mx-6 px-6 py-3 rounded-b-lg">
                                    <span class="text-sm font-bold text-gray-900">PROFIT:</span>
                                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($stockOut->total - $totalModal, 0, ',', '.') }}</span>
                                </div>

                                <div class="pt-3 text-center">
                                    <p class="text-xs text-gray-500">
                                        Margin:
                                        <span class="font-semibold">
                                            {{ $totalModal > 0 ? number_format((($stockOut->total - $totalModal) / $totalModal) * 100, 1) : 0 }}%
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>

                            <div class="space-y-2">
                                <button onclick="window.print()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    🖨️ Print / PDF
                                </button>

                                <a href="{{ route('stock-outs.create') }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    + Transaksi Baru
                                </a>

                                <form action="{{ route('stock-outs.destroy', $stockOut) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        🗑️ Hapus Transaksi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            .no-print { display: none; }
            body { font-size: 12px; }
        }
    </style>
    @endpush
</x-app-layout>
