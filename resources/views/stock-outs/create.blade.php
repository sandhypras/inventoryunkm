<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi Barang Keluar / Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('stock-outs.store') }}" method="POST" id="stockOutForm">
                        @csrf

                        <!-- Header Information -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
                            <!-- Tanggal -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal *</label>
                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Customer -->
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Customer</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nama pembeli (opsional)">
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Transaksi (Auto) -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Kode Transaksi</label>
                                <input type="text" name="code" id="code" value="{{ old('code', $code) }}" required readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500">Otomatis di-generate</p>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Item Barang Keluar</h3>
                                <button type="button" onclick="addItem()"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    + Tambah Item
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsContainer" class="bg-white divide-y divide-gray-200">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Catatan tambahan untuk transaksi ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-between text-lg font-bold">
                                <span class="text-gray-700">Total Penjualan:</span>
                                <span class="text-green-600" id="grandTotal">Rp 0</span>
                            </div>
                            <input type="hidden" name="total" id="totalInput" value="0">
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-x-3">
                            <a href="{{ route('stock-outs.index') }}"
                                class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                💾 Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemIndex = 0;
        const products = @json($products);

        function addItem() {
            const container = document.getElementById('itemsContainer');
            const row = document.createElement('tr');
            row.id = `item-${itemIndex}`;

            row.innerHTML = `
                <td class="px-4 py-3">
                    <select name="items[${itemIndex}][product_id]" required onchange="updateItemPrice(${itemIndex})"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Produk</option>
                        ${products.map(p => `
                            <option value="${p.id}"
                                data-stock="${p.stock}"
                                data-price="${p.selling_price}"
                                data-unit="${p.unit}">
                                ${p.code} - ${p.name}
                            </option>
                        `).join('')}
                    </select>
                </td>
                <td class="px-4 py-3">
                    <span id="stock-${itemIndex}" class="text-sm font-semibold text-gray-700">-</span>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required
                        onchange="calculateSubtotal(${itemIndex})"
                        class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="items[${itemIndex}][price]" min="0" step="0.01" required
                        onchange="calculateSubtotal(${itemIndex})"
                        class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </td>
                <td class="px-4 py-3">
                    <span id="subtotal-${itemIndex}" class="text-sm font-semibold text-gray-900">Rp 0</span>
                </td>
                <td class="px-4 py-3">
                    <button type="button" onclick="removeItem(${itemIndex})"
                        class="text-red-600 hover:text-red-900 font-medium text-sm">
                        Hapus
                    </button>
                </td>
            `;

            container.appendChild(row);
            itemIndex++;
        }

        function updateItemPrice(index) {
            const select = document.querySelector(`select[name="items[${index}][product_id]"]`);
            const option = select.options[select.selectedIndex];
            const price = option.dataset.price || 0;
            const stock = option.dataset.stock || 0;
            const unit = option.dataset.unit || '';

            document.querySelector(`input[name="items[${index}][price]"]`).value = price;
            document.getElementById(`stock-${index}`).textContent = `${stock} ${unit}`;

            const qtyInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
            qtyInput.max = stock;

            calculateSubtotal(index);
        }

        function calculateSubtotal(index) {
            const qty = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
            const price = parseFloat(document.querySelector(`input[name="items[${index}][price]"]`).value) || 0;
            const subtotal = qty * price;

            document.getElementById(`subtotal-${index}`).textContent = formatRupiah(subtotal);
            calculateTotal();
        }

        function removeItem(index) {
            document.getElementById(`item-${index}`).remove();
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                const value = el.textContent.replace(/[^0-9]/g, '');
                total += parseFloat(value) || 0;
            });

            document.getElementById('grandTotal').textContent = formatRupiah(total);
            document.getElementById('totalInput').value = total;
        }

        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Add first item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addItem();
        });

        // Validate stock before submit
        document.getElementById('stockOutForm').addEventListener('submit', function(e) {
            let valid = true;
            const selects = document.querySelectorAll('select[name^="items"]');

            selects.forEach((select, idx) => {
                if (!select.value) return; // Skip empty selects

                const option = select.options[select.selectedIndex];
                const stock = parseFloat(option.dataset.stock) || 0;
                const qtyInput = document.querySelector(`input[name="items[${idx}][quantity]"]`);
                const qty = parseFloat(qtyInput.value) || 0;

                if (qty > stock) {
                    alert(`Stok ${option.text.split(' - ')[1]} tidak mencukupi! Stok tersedia: ${stock}`);
                    valid = false;
                    qtyInput.focus();
                    return false;
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
    @endpush
</x-app-layout>
