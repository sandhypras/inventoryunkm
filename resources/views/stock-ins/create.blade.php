<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Barang Masuk') }}
            </h2>
            <a href="{{ route('stock-ins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('stock-ins.store') }}" method="POST" id="stockInForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Transaksi</label>
                            <input type="text" value="{{ $code }}" readonly
                                class="w-full rounded-lg border-gray-300 bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                            <select name="supplier_id" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }} - {{ $supplier->company }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="2"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                    </div>

                    <hr class="my-6">

                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Produk</h3>
                        <button type="button" onclick="addProductRow()"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            + Tambah Produk
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="productTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows will be added here -->
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold">Total:</td>
                                    <td class="px-4 py-3 font-bold text-lg text-blue-600" id="grandTotal">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('stock-ins.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let rowIndex = 0;
        const products = @json($products);

        function addProductRow() {
            const tbody = document.getElementById('productTableBody');
            const row = document.createElement('tr');
            row.id = `row-${rowIndex}`;

            row.innerHTML = `
                <td class="px-4 py-3">
                    <select name="products[${rowIndex}][product_id]" required onchange="updatePrice(${rowIndex})"
                        class="w-full rounded border-gray-300 focus:border-blue-500" id="product-${rowIndex}">
                        <option value="">Pilih Produk</option>
                        ${products.map(p => `<option value="${p.id}" data-price="${p.purchase_price}">${p.name} (${p.code})</option>`).join('')}
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="products[${rowIndex}][quantity]" required min="1" value="1"
                        oninput="calculateSubtotal(${rowIndex})"
                        class="w-24 rounded border-gray-300 focus:border-blue-500" id="quantity-${rowIndex}">
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="products[${rowIndex}][price]" required min="0" step="0.01"
                        oninput="calculateSubtotal(${rowIndex})"
                        class="w-32 rounded border-gray-300 focus:border-blue-500" id="price-${rowIndex}">
                </td>
                <td class="px-4 py-3">
                    <span class="font-semibold text-gray-700" id="subtotal-${rowIndex}">Rp 0</span>
                </td>
                <td class="px-4 py-3">
                    <button type="button" onclick="removeRow(${rowIndex})"
                        class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
            rowIndex++;
        }

        function updatePrice(index) {
            const select = document.getElementById(`product-${index}`);
            const priceInput = document.getElementById(`price-${index}`);
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                const price = selectedOption.dataset.price;
                priceInput.value = price;
                calculateSubtotal(index);
            }
        }

        function calculateSubtotal(index) {
            const quantity = parseFloat(document.getElementById(`quantity-${index}`).value) || 0;
            const price = parseFloat(document.getElementById(`price-${index}`).value) || 0;
            const subtotal = quantity * price;

            document.getElementById(`subtotal-${index}`).textContent =
                'Rp ' + subtotal.toLocaleString('id-ID', {minimumFractionDigits: 0});

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                const value = el.textContent.replace('Rp ', '').replace(/\./g, '').replace(',', '.');
                total += parseFloat(value) || 0;
            });

            document.getElementById('grandTotal').textContent =
                'Rp ' + total.toLocaleString('id-ID', {minimumFractionDigits: 0});
        }

        function removeRow(index) {
            document.getElementById(`row-${index}`).remove();
            calculateGrandTotal();
        }

        // Add first row on load
        addProductRow();
    </script>
    @endpush
</x-app-layout>
