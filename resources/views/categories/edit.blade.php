<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Kategori -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon Kategori -->
                        <div class="mb-6">
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon Kategori</label>
                            <div class="grid grid-cols-8 gap-2">
                                @php
                                    $icons = ['📦', '🛒', '🍔', '🥤', '👕', '📱', '💻', '🏠', '🚗', '⚽', '📚', '🎮', '🎨', '🔧', '💊', '🌿'];
                                @endphp
                                @foreach($icons as $emoji)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="icon" value="{{ $emoji }}"
                                            class="peer sr-only" {{ old('icon', $category->icon) == $emoji ? 'checked' : '' }}>
                                        <div class="text-2xl p-2 text-center rounded-lg border-2 border-gray-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:border-indigo-400 transition">
                                            {{ $emoji }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-x-3">
                            <a href="{{ route('categories.index') }}"
                                class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Update Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
