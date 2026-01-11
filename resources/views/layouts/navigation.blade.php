<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group transition-transform duration-200 hover:scale-105">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                            <span class="text-white font-bold text-xl animate-pulse">📦</span>
                        </div>
                        <span class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent group-hover:from-purple-600 group-hover:to-indigo-600 transition-all duration-300">
                            Inventory UMKM
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dashboard - All Roles -->
                    @can('view_dashboard')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            📊 {{ __('Dashboard') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Products - All except Viewer (but Viewer can view) -->
                    @can('view_products')
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            📦 {{ __('Produk') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Categories - Admin & Gudang only -->
                    @can('view_categories')
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            📁 {{ __('Kategori') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Suppliers - Admin & Gudang only -->
                    @can('view_suppliers')
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            🏢 {{ __('Supplier') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Stock In - Admin & Gudang only -->
                    @can('view_stock_in')
                    <x-nav-link :href="route('stock-ins.index')" :active="request()->routeIs('stock-ins.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            📥 {{ __('Barang Masuk') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Stock Out - All except Viewer -->
                    @can('view_stock_out')
                    <x-nav-link :href="route('stock-outs.index')" :active="request()->routeIs('stock-outs.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            📤 {{ __('Barang Keluar') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Reports - All Roles -->
                    @can('view_reports')
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            📈 {{ __('Laporan') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan

                    <!-- Users - Admin only -->
                    @can('view_users')
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="group relative">
                        <span class="flex items-center gap-1 transition-colors duration-200 group-hover:text-indigo-600">
                            👥 {{ __('Users') }}
                        </span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
                    </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Role Badge -->
                <div class="mr-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ auth()->user()->getRoleBadgeColor() }} shadow-sm hover:shadow-md transition-shadow duration-200 animate-pulse">
                        {{ auth()->user()->getRoleDisplayName() }}
                    </span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-1 transition-transform duration-200" :class="{'rotate-180': open}">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <p class="text-sm text-gray-500">Logged in as</p>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-indigo-50 transition-colors duration-200">
                            ⚙️ {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        @can('view_settings')
                        <x-dropdown-link :href="route('dashboard')" class="hover:bg-indigo-50 transition-colors duration-200">
                            🔧 {{ __('System Settings') }}
                        </x-dropdown-link>
                        @endcan

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="hover:bg-red-50 transition-colors duration-200">
                                🚪 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="h-6 w-6 transition-transform duration-200" :class="{'rotate-45': open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-200 transform transition-all duration-300 ease-in-out" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Dashboard -->
            @can('view_dashboard')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:bg-indigo-100 transition-colors duration-200">
                📊 {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Products -->
            @can('view_products')
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                📦 {{ __('Produk') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Categories -->
            @can('view_categories')
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                📁 {{ __('Kategori') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Suppliers -->
            @can('view_suppliers')
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                🏢 {{ __('Supplier') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Stock In -->
            @can('view_stock_in')
            <x-responsive-nav-link :href="route('stock-ins.index')" :active="request()->routeIs('stock-ins.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                📥 {{ __('Barang Masuk') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Stock Out -->
            @can('view_stock_out')
            <x-responsive-nav-link :href="route('stock-outs.index')" :active="request()->routeIs('stock-outs.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                📤 {{ __('Barang Keluar') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Reports -->
            @can('view_reports')
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                📈 {{ __('Laporan') }}
            </x-responsive-nav-link>
            @endcan

            <!-- Users -->
            @can('view_users')
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="hover:bg-indigo-100 transition-colors duration-200">
                👥 {{ __('Users') }}
            </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-white">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="mt-1">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ auth()->user()->getRoleBadgeColor() }} shadow-sm">
                        {{ auth()->user()->getRoleDisplayName() }}
                    </span>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="hover:bg-indigo-50 transition-colors duration-200">
                    ⚙️ {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="hover:bg-red-50 transition-colors duration-200">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
