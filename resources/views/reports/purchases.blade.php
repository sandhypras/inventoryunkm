<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📥 Laporan Pembelian
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-indigo-100 text-sm">Total Transaksi</p>
                    <p class="text-3xl font-bold mt-1">{{ $summary['total_transactions'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-orange-100 text-sm">Total Pembelian</p>
                    <p class="text-3xl font-bold mt-1">Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-teal-100 text-sm">Total Item Dibeli</p>
                    <p class="text-3xl font-bold mt-1">{{ $summary['total_items'] }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Export Laporan</h3>
                        <div class="flex gap-2">
                            <form action="{{ route('reports.purchases.pdf') }}" method="GET" class="inline">
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📧 Kirim Laporan Pembelian</h3>

                    <form action="{{ route('reports.purchases.email') }}" method="POST">
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
                                • Laporan Pembelian (PDF)<br>
                                • {{ $summary['total_transactions'] }} transaksi<br>
                                • Total: Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}
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
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <select name="supplier" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">🔍 Filter</button>
                            <a href="{{ route('reports.purchases') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Items</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions as $trans)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $trans->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trans->date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trans->supplier->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">{{ $trans->items->count() }} item</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-orange-600">
                                        Rp {{ number_format($trans->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <a href="{{ route('stock-ins.show', $trans) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
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
