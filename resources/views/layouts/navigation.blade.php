<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('GAMBAR/LOGOCIAST.png') }}" alt="Logo" class="block w-auto h-9">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Home') }}
                    </x-nav-link>

                    <!-- Maklumat Kakitangan with Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-6 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                            Maklumat Kakitangan
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-1 transition-transform"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition
                            class="absolute left-0 z-50 w-48 mt-2 bg-white rounded-md shadow-lg">
                            <a href="{{ route('kakitangan.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Maklumat Peribadi
                            </a>
                            @if(auth()->user()->level == 1 || auth()->user()->level == 2 || auth()->user()->level == 3)
                                <a href="{{ route('kakitangan.index') }}" @click="open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                    Maklumat Kakitangan
                                </a>
                            @endif
                            @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                <a href="{{ route('kakitangan.create') }}" @click="open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                    Tambah Maklumat
                                </a>
                            @endif
                            <a href="{{ route('surat.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Surat Pengesahan
                            </a>
                            <a href="{{ route('gaji.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Maklumat Gaji
                            </a>
                            <a href="{{ route('keluarga.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Keluarga
                            </a>
                            <a href="{{ route('surat_akuan.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Surat Akuan Perubatan
                            </a>
                            <a href="{{ route('latihan.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Latihan
                            </a>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-6 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                            Laporan
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-1 transition-transform"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition
                            class="absolute left-0 z-50 w-64 mt-2 bg-white rounded-md shadow-lg">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b bg-gray-50">
                                Kakitangan
                            </div>
                            <a href="{{ route('laporan.pengisian_gred') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Pengisian Jawatan Mengikut Gred
                            </a>
                            <a href="{{ route('laporan.teknikal') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Pengisian Jawatan Teknikal
                            </a>
                            <a href="{{ route('laporan.lantikan') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Maklumat Lantikan Penyandang
                            </a>
                            <a href="{{ route('laporan.senarai_perjawatan') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Senarai Nama & Perjawatan
                            </a>
                            <a href="{{ route('laporan.penempatan') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Penempatan Kakitangan
                            </a>
                            <a href="{{ route('laporan.luar_ciast') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Kakitangan diluar CIAST
                            </a>
                            <a href="{{ route('laporan.sambilan') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Kakitangan Sambilan
                            </a>
                            <a href="{{ route('laporan.statistik') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Statistik Kakitangan
                            </a>
                            <a href="{{ route('laporan.bersara') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Kakitangan Bersara
                            </a>
                            <a href="{{ route('laporan.baru') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Kakitangan Baru
                            </a>
                            <a href="{{ route('laporan.bertukar') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Kakitangan Bertukar
                            </a>
                            <a href="{{ route('laporan.apc_pingat') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Senarai APC dan Pingat
                            </a>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-6 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                            Pentadbir Sistem
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-1 transition-transform"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition
                            class="absolute left-0 z-50 w-48 mt-2 bg-white rounded-md shadow-lg">
                            <a href="{{ route('kakitangan.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Tukar Katalaluan
                            </a>
                            @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                <a href="{{ route('kakitangan.create') }}" @click="open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                    Penetapan
                                </a>
                            @endif
                            <a href="#" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Surat Pengesahan
                            </a>
                            <a href="#" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Surat Akuan Perubatan
                            </a>
                            <a href="#" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Latihan
                            </a>

                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-6 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                            Bantuan
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-1 transition-transform"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition
                            class="absolute left-0 z-50 w-48 mt-2 bg-white rounded-md shadow-lg">
                            <a href="{{ route('kakitangan.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Tentang ePerjawatan
                            </a>
                            @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                <a href="{{ route('kakitangan.create') }}" @click="open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                    Manual
                                </a>
                            @endif
                            <a href="#" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Surat Pengesahan
                            </a>
                            <a href="#" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Surat Akuan Perubatan
                            </a>
                            <a href="#" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 border-t hover:bg-gray-100">
                                Cadangan
                            </a>

                        </div>
                    </div>
                    {{-- <x-nav-link :href="route('pentadbir.index')" :active="request()->routeIs('pentadbir')">
                        {{ __('Pentadbir Sistem') }}
                    </x-nav-link>
                    <x-nav-link :href="route('bantuan.index')" :active="request()->routeIs('bantuan')">
                        {{ __('Bantuan') }}
                    </x-nav-link> --}}
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            <!-- Mobile Submenu for Maklumat Kakitangan -->
            <div class="px-3 py-2 pt-2 pb-3">
                <div class="mb-2 text-sm font-medium text-gray-600">Maklumat Kakitangan</div>
                <div class="pl-4 space-y-1">
                    <a href="{{ route('kakitangan.index') }}"
                        class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                        Maklumat Peribadi
                    </a>
                    @if(auth()->user()->level == 1 || auth()->user()->level == 2 || auth()->user()->level == 3)
                        <a href="{{ route('kakitangan.index') }}"
                            class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                            Maklumat Kakitangan
                        </a>
                    @endif
                    @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                        <a href="{{ route('kakitangan.create') }}"
                            class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                            Tambah Maklumat
                        </a>
                    @endif
                    <a href="#"
                        class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                        Surat Pengesahan
                    </a>
                    <a href="#"
                        class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                        Maklumat Gaji
                    </a>
                    <a href="{{ route('keluarga.index') }}"
                        class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                        Keluarga
                    </a>
                    <a href="#"
                        class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                        Surat Akuan Perubatan
                    </a>
                    <a href="#"
                        class="block px-3 py-2 text-sm text-gray-600 rounded hover:text-gray-900 hover:bg-gray-50">
                        Latihan
                    </a>
                </div>
            </div>

            <!--   
            <x-responsive-nav-link :href="route('pentadbir.index')" :active="request()->routeIs('pentadbir')">
                {{ __('Pentadbir Sistem') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bantuan.index')" :active="request()->routeIs('bantuan')">
                {{ __('Bantuan') }}
            </x-responsive-nav-link> -->
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>