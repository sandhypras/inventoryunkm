<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✏️ Edit User: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                                placeholder="user@example.com">
                            @error('email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror"
                                placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Role/Jabatan <span class="text-red-500">*</span>
                            </label>
                            <select name="role" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('role') border-red-500 @enderror">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    👑 Administrator (Full Access)
                                </option>
                                <option value="gudang" {{ old('role', $user->role) == 'gudang' ? 'selected' : '' }}>
                                    📦 Staff Gudang (Stock Management)
                                </option>
                                <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>
                                    💰 Kasir (Sales Only)
                                </option>
                                <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>
                                    👁️ Viewer (Read Only)
                                </option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Current Role Badge -->
                            <div class="mt-2">
                                <span class="text-sm text-gray-600">Role saat ini: </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->getRoleBadgeColor() }}">
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </div>
                        </div>

                        <hr class="my-6">

                        <!-- Password Section -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Password Baru
                            </label>
                            <input type="password" name="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <p class="text-xs text-gray-500 mt-1">
                                💡 Kosongkan field ini jika tidak ingin mengubah password
                            </p>
                            @error('password')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Ketik ulang password baru">
                        </div>

                        <!-- User Info -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <strong>Dibuat:</strong> {{ $user->created_at->format('d F Y H:i') }}<br>
                                <strong>Terakhir Update:</strong> {{ $user->updated_at->diffForHumans() }}<br>
                                <strong>Status:</strong>
                                @if($user->email_verified_at)
                                    <span class="text-green-600">✓ Aktif</span>
                                @else
                                    <span class="text-red-600">✗ Nonaktif</span>
                                @endif
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between gap-4">
                            <button type="submit"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition">
                                💾 Update User
                            </button>
                            <a href="{{ route('users.index') }}"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded text-center transition">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            @if($user->id !== auth()->id())
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-red-600 mb-2">⚠️ Danger Zone</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Tindakan ini tidak dapat dibatalkan. Harap berhati-hati!
                    </p>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            🗑️ Hapus User Ini
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
