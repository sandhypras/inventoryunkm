<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💰 Laporan Keuntungan
            </h2>
            <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Kembali ke Menu Laporan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert -->
            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                ❌ {{ session('error') }}
            </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-green-100 text-sm">Total Penjualan</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-orange-100 text-sm">Total Pembelian</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-blue-100 text-sm">Keuntungan Bersih</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-purple-100 text-sm">Margin Keuntungan</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($summary['profit_margin'], 2) }}%</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Export Laporan</h3>
                        <div class="flex gap-2">
                            <form action="{{ route('reports.profit.pdf') }}" method="GET" class="inline">
                                @foreach(request()->all() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download PDF
                                </button>
                            </form>

                            @can('email_reports')
                            <button onclick="toggleEmailModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Kirim ke Email
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Modal -->
            <div id="emailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📧 Kirim Laporan Keuntungan</h3>

                    <form action="{{ route('reports.profit.email') }}" method="POST">
                        @csrf
                        @foreach(request()->all() as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email Owner</label>
                            <input type="email" name="owner_email" value="{{ App\Models\Setting::get('owner_email', 'owner@example.com') }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                            <p class="text-sm text-blue-800">
                                <strong>📋 Yang akan dikirim:</strong><br>
                                • Laporan Keuntungan (PDF)<br>
                                • Periode: {{ date('d M Y', strtotime($summary['start_date'])) }} - {{ date('d M Y', strtotime($summary['end_date'])) }}<br>
                                • Profit: Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }} ({{ number_format($summary['profit_margin'], 2) }}%)
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Kirim</button>
                            <button type="button" onclick="toggleEmailModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date', $summary['start_date']) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date', $summary['end_date']) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">🔍 Filter</button>
                            <a href="{{ route('reports.profit') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Profit Analysis -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📊 Analisis Keuntungan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Chart Placeholder -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-700 mb-2">Perbandingan Penjualan vs Pembelian</h4>
                            <div class="h-48 flex items-center justify-center text-gray-500">
                                <div class="text-center">
                                    <p class="mb-2">📈 Penjualan: Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p>
                                    <p class="mb-2">📉 Pembelian: Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}</p>
                                    <p class="text-xl font-bold text-green-600">💰 Profit: Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Profit Margin Gauge -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-700 mb-2">Margin Keuntungan</h4>
                            <div class="h-48 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="relative inline-block">
                                        <svg class="w-32 h-32 transform -rotate-90">
                                            <circle cx="64" cy="64" r="56" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                            <circle cx="64" cy="64" r="56"
                                                stroke="{{ $summary['profit_margin'] > 30 ? '#10b981' : ($summary['profit_margin'] > 15 ? '#f59e0b' : '#ef4444') }}"
                                                stroke-width="8"
                                                fill="none"
                                                stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                                                stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - $summary['profit_margin'] / 100) }}"
                                                stroke-linecap="round"/>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-3xl font-bold">{{ number_format($summary['profit_margin'], 1) }}%</span>
                                        </div>
                                    </div>
                                    <p class="mt-4 text-sm text-gray-600">
                                        @if($summary['profit_margin'] > 30)
                                            ✅ Margin Sangat Bagus!
                                        @elseif($summary['profit_margin'] > 15)
                                            ⚠️ Margin Cukup Baik
                                        @else
                                            ❌ Margin Perlu Ditingkatkan
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Profit Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">💎 Keuntungan per Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty Terjual</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Modal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Profit</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Margin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($productProfits as $item)
                                @php
                                    $margin = $item['revenue'] > 0 ? ($item['profit'] / $item['revenue']) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item['product']->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['product']->category->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        {{ $item['quantity_sold'] }} {{ $item['product']->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        Rp {{ number_format($item['revenue'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        Rp {{ number_format($item['cost'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $item['profit'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($item['profit'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $margin > 30 ? 'bg-green-100 text-green-800' : ($margin > 15 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ number_format($margin, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data penjualan dalam periode ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if(count($productProfits) > 0)
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">TOTAL</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right text-green-600">Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-center">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ number_format($summary['profit_margin'], 1) }}%
                                        </span>
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

    <script>
        function toggleEmailModal() {
            document.getElementById('emailModal').classList.toggle('hidden');
        }

        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) toggleEmailModal();
        });
    </script>
</x-app-layout>
