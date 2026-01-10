<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kategori') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kategori *
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Icon (Emoji)
                        </label>
                        <input type="text" name="icon" value="{{ old('icon') }}" maxlength="10"
                            placeholder="Contoh: 📦, 💻, 👕"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <p class="text-gray-500 text-xs mt-1">Gunakan emoji untuk icon kategori</p>

                        <!-- Icon Suggestions -->
                        <div class="mt-3 flex gap-2 flex-wrap">
                            <span class="text-sm text-gray-600">Saran:</span>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='📦'" class="text-2xl hover:scale-125 transition">📦</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='💻'" class="text-2xl hover:scale-125 transition">💻</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='👕'" class="text-2xl hover:scale-125 transition">👕</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='🍔'" class="text-2xl hover:scale-125 transition">🍔</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='🥤'" class="text-2xl hover:scale-125 transition">🥤</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='✏️'" class="text-2xl hover:scale-125 transition">✏️</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='🏠'" class="text-2xl hover:scale-125 transition">🏠</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='🎮'" class="text-2xl hover:scale-125 transition">🎮</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='📱'" class="text-2xl hover:scale-125 transition">📱</button>
                            <button type="button" onclick="document.querySelector('[name=icon]').value='🔧'" class="text-2xl hover:scale-125 transition">🔧</button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('categories.index') }}"
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
</x-app-layout>
