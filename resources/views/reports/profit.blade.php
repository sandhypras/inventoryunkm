<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Profit & Margin') }}
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
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Modal</p>
                        <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($summary['total_cost'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Profit</p>
                        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Rata-rata Margin</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($summary['avg_margin'], 1) }}%</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $summary['total_transactions'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.profit') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                        <div class="md:col-span-2 flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Filter
                            </button>
                            <a href="{{ route('reports.profit') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Reset
                            </a>
                            <button type="button" onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                🖨️ Print
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Profit by Category -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profit per Kategori</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pendapatan</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Modal</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Profit</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Margin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($profitByCategory as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $category->icon }} {{ $category->name }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-green-600">
                                            Rp {{ number_format($category->revenue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-blue-600">
                                            Rp {{ number_format($category->cost, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                                            Rp {{ number_format($category->profit, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($category->margin >= 50) bg-green-100 text-green-800
                                                @elseif($category->margin >= 30) bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ number_format($category->margin, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Tidak ada data profit
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($profitByCategory->isNotEmpty())
                                <tfoot class="bg-gray-50 font-semibold">
                                    <tr>
                                        <td class="px-4 py-3 text-right text-sm text-gray-900">TOTAL:</td>
                                        <td class="px-4 py-3 text-right text-sm text-green-600">
                                            Rp {{ number_format($profitByCategory->sum('revenue'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-blue-600">
                                            Rp {{ number_format($profitByCategory->sum('cost'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-green-600">
                                            Rp {{ number_format($profitByCategory->sum('profit'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-purple-600">
                                            {{ $profitByCategory->sum('cost') > 0 ? number_format(($profitByCategory->sum('profit') / $profitByCategory->sum('cost')) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detailed Profit per Transaction -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Profit per Transaksi</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pendapatan</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Modal</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Profit</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Margin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($profitData as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($data['date'])->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-blue-600">
                                            {{ $data['code'] }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $data['customer'] }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-green-600">
                                            Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-blue-600">
                                            Rp {{ number_format($data['cost'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                                            Rp {{ number_format($data['profit'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($data['margin'] >= 50) bg-green-100 text-green-800
                                                @elseif($data['margin'] >= 30) bg-blue-100 text-blue-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ number_format($data['margin'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                            Tidak ada transaksi pada periode ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($profitData) > 0)
                                <tfoot class="bg-gray-50 font-semibold">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right text-sm text-gray-900">TOTAL:</td>
                                        <td class="px-4 py-3 text-right text-sm text-green-600">
                                            Rp {{ number_format(collect($profitData)->sum('revenue'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-blue-600">
                                            Rp {{ number_format(collect($profitData)->sum('cost'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-green-600">
                                            Rp {{ number_format(collect($profitData)->sum('profit'), 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-purple-600">
                                            {{ collect($profitData)->sum('cost') > 0 ? number_format((collect($profitData)->sum('profit') / collect($profitData)->sum('cost')) * 100, 1) : 0 }}%
                                        </td>
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
