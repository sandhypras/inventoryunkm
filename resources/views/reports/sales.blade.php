<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Penjualan') }}
            </h2>
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Penjualan</p>
                        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $summary['total_transactions'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Modal</p>
                        <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Profit</p>
                        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Rata-rata/Transaksi</p>
                        <p class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($summary['avg_transaction'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.sales') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode/Customer..."
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Filter
                            </button>
                            <a href="{{ route('reports.sales') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Best Selling Products -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Terjual</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($bestSelling as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->code }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">{{ number_format($item->total_qty, 0) }}</td>
                                            <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">
                                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                                Tidak ada data penjualan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Period Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Periode</h3>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-600 mb-1">Periode</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                                </p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Total Penjualan:</span>
                                    <span class="text-sm font-semibold text-green-600">
                                        Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Total Modal:</span>
                                    <span class="text-sm font-semibold text-blue-600">
                                        Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span class="text-sm font-bold text-gray-900">Profit:</span>
                                    <span class="text-sm font-bold text-green-600">
                                        Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-xs text-gray-600 mb-1">Margin Keuntungan</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ $summary['total_modal'] > 0 ? number_format(($summary['total_profit'] / $summary['total_modal']) * 100, 1) : 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Transaksi Penjualan</h3>
                        <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            🖨️ Print
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Items</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stockOuts as $stockOut)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($stockOut->date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('stock-outs.show', $stockOut) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                {{ $stockOut->code }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $stockOut->customer_name ?: 'Umum' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ $stockOut->items->count() }} item
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                                            Rp {{ number_format($stockOut->total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $stockOut->user->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Tidak ada transaksi penjualan pada periode ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($stockOuts->isNotEmpty())
                                <tfoot class="bg-gray-50 font-semibold">
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right text-sm text-gray-900">TOTAL:</td>
                                        <td class="px-4 py-3 text-right text-sm text-green-600">
                                            Rp {{ number_format($stockOuts->sum('total'), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            .no-print { display: none !important; }
            header, nav, button, a { display: none !important; }
        }
    </style>
    @endpush
</x-app-layout>
