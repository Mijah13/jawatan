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
                        <div x-show="open" x-transition style="display: none;"
                            class="absolute left-0 z-50 w-48 mt-2 bg-white rounded-md shadow-lg">
                            <!-- <a href="{{ route('kakitangan.index') }}" @click="open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Maklumat Peribadi
                            </a> -->
                            @if(auth()->user()->level == 1 || auth()->user()->level == 2 || auth()->user()->level == 3 )
                                <a href="{{ route('kakitangan.carian') }}" @click="open = false"
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
                    <div class="relative z-50" x-data="{ open: false, sub: null }" @click.away="open=false; sub=null">

                        <!-- ===== BUTTON LAPORAN ===== -->
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-6 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Laporan
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-1 transition-transform duration-200"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- ===== DROPDOWN UTAMA (MEGA MENU) ===== -->
                        <div x-show="open" x-transition style="width: 300px; display: none;"
                            class="absolute left-0 mt-2 bg-white rounded-md shadow-lg z-50 overflow-hidden ring-1 ring-black ring-opacity-5">
                            <div class="p-4 grid grid-cols-4 gap-4">

                                <!-- ===== KAKITANGAN (Column 1-3) ===== -->
                                <div class="col-span-3">
                                    <h3
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-1">
                                        Kakitangan
                                    </h3>
                                    <div class="grid grid-cols-3 gap-x-4 gap-y-2">
                                        <a href="{{ route('laporan.pengisian_gred') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Pengisian Jawatan Mengikut Gred
                                        </a>
                                        <a href="{{ route('laporan.teknikal') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Pengisian Jawatan Teknikal
                                        </a>
                                        <a href="{{ route('laporan.lantikan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Maklumat Lantikan Penyandang
                                        </a>
                                        <a href="{{ route('laporan.senarai_perjawatan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai Nama & Perjawatan
                                        </a>
                                        <a href="{{ route('laporan.penempatan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Penempatan Kakitangan
                                        </a>
                                        <a href="{{ route('laporan.luar_ciast') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Kakitangan Luar CIAST
                                        </a>
                                        <a href="{{ route('laporan.sambilan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Kakitangan Sambilan
                                        </a>
                                        <a href="{{ route('laporan.statistik') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Statistik Kakitangan
                                        </a>
                                        <a href="{{ route('laporan.bersara') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Kakitangan Bersara
                                        </a>
                                        <a href="{{ route('laporan.baru') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Kakitangan Baru
                                        </a>
                                        <a href="{{ route('laporan.bertukar') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Kakitangan Bertukar
                                        </a>
                                        <a href="{{ route('laporan.apc_pingat') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai APC & Pingat
                                        </a>
                                    </div>
                                </div>

                                <!-- ===== LATIHAN (Column 4) ===== -->
                                @if(auth()->user()->level == 1 || auth()->user()->level == 2 || auth()->user()->level == 3 )

                                <div class="col-span-1 border-l">
                                    <h3
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-1">
                                        Isytihar Harta
                                    </h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('laporan.senarai_isytihar') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai Isytihar Harta Mengikut Gred
                                        </a>
                                        <a href="{{ route('laporan.gagal_isytihar') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai Gagal Isytihar Harta
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="col-span-1 border-l">
                                    <h3
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-1">
                                        Latihan
                                    </h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('latihan.senarai') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai Latihan
                                        </a>
                                        <a href="#"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Laporan Latihan
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->level == 1 || auth()->user()->level == 2)

                    <div class="relative z-50" x-data="{ open: false, sub: null }" @click.away="open=false; sub=null">

                        <!-- ===== BUTTON LAPORAN ===== -->
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-6 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Pentadbir Sistem
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-1 transition-transform duration-200"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- ===== DROPDOWN UTAMA (MEGA MENU) ===== -->
                        <div x-show="open" x-transition style="width: 300px; display: none;"
                            class="absolute left-0 mt-2 bg-white rounded-md shadow-lg z-50 overflow-hidden ring-1 ring-black ring-opacity-5">
                            <div class="p-4 grid grid-cols-4 gap-4">

                                <!-- ===== KAKITANGAN (Column 1-3) ===== -->
                                <div class="col-span-3">
                                    <h3
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-1">
                                        Penetapan
                                    </h3>
                                    <div class="grid grid-cols-3 gap-x-4 gap-y-2">
                                        <a href="{{ route('pentadbir.peringkat_sumbangan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Peringkat Sumbangan
                                        </a>
                                        <a href="{{ route('pentadbir.program') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Program
                                        </a>
                                        <a href="{{ route('pentadbir.unit') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Unit
                                        </a>
                                        <a href="{{ route('pentadbir.jenis_isytihar') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Jenis Isytihar Harta
                                        </a>
                                        <a href="{{ route('pentadbir.jenis_penempatan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Jenis Penempatan
                                        </a>
                                        <a href="{{ route('pentadbir.jawatan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Jawatan
                                        </a>
                                        <a href="{{ route('pentadbir.gred') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Gred
                                        </a>
                                        <a href="{{ route('pentadbir.perjawatan') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Perjawatan
                                        </a>
                                        <a href="{{ route('pentadbir.elaun') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai / Tambah / Edit Maklumat Elaun
                                        </a>
                                        <a href="{{ route('pentadbir.moto_hari_pekerja') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai / Tambah / Edit Moto Hari Pekerja
                                        </a>

                                    </div>
                                </div>

                                <div class="col-span-1 border-l">
                                    <h3
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-1">
                                        Surat Pengesahan
                                    </h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('pentadbir.surat_pengesahan_cari') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Cari Pemohon
                                        </a>
                                        <a href="{{ route('pentadbir.surat_pengesahan_pelulus') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Pelulus
                                        </a>
                                    </div>
                                </div>


                                <div class="col-span-1 border-l">
                                    <h3
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-1">
                                        Surat Akuan Perubatan
                                    </h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('pentadbir.surat_akuan_senarai') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Senarai Pemohon
                                        </a>
                                        <a href="{{ route('pentadbir.surat_akuan_pelulus') }}"
                                            class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                            Pelulus
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Bantuan Dropdown -->
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

                        <!-- Mega Menu -->
                        <div x-show="open" x-transition class="absolute left-0 z-50 mt-2 bg-white rounded-md shadow-lg"
                            style="width: 300px; display: none;">
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div>
                                        <h3
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-2">
                                            Maklumat Umum
                                        </h3>
                                        <div class="space-y-2">
                                            <a href="{{ route('bantuan.tentang') }}" @click="open = false"
                                                class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1.5 block">
                                                📖 Tentang ePerjawatan
                                            </a>
                                            @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                                <a href="{{ route('bantuan.manual') }}" @click="open = false"
                                                    class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1.5 block">
                                                    📚 Manual Pengguna
                                                </a>
                                            @endif
                                            <!-- <a href="{{ route('bantuan.cadangan') }}" @click="open = false"
                                                class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1.5 block">
                                                💡 Cadangan & Maklum Balas
                                            </a> -->
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div>
                                        <h3
                                            class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 border-b pb-2">
                                            Panduan Surat
                                        </h3>
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-xs font-medium text-gray-600 mb-1.5">Surat Pengesahan</p>
                                                <div class="space-y-1 ml-2">
                                                    <a href="{{ route('bantuan.manual_permohonan_surat_pengesahan') }}"
                                                        @click="open = false"
                                                        class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                                        → Permohonan
                                                    </a>
                                                    <a href="{{ route('bantuan.manual_pelulus_surat_pengesahan') }}"
                                                        @click="open = false"
                                                        class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                                        → Pelulus
                                                    </a>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-600 mb-1.5">Surat Akuan
                                                    Perubatan</p>
                                                <div class="space-y-1 ml-2">
                                                    <a href="{{ route('bantuan.manual_surat_akuan_perubatan') }}"
                                                        @click="open = false"
                                                        class="text-sm text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 py-1 block">
                                                        → Panduan
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->nama }}</div>

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
                        <x-dropdown-link :href="url('/kakitangan')">
                            {{ __('Profil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('password.edit')">
                            {{ __('Tukar Kata Laluan') }}
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
    <!-- Responsive Navigation Menu -->
    <div x-show="open" class="sm:hidden" style="display: none;">

        <div class="pt-2 pb-3 space-y-2">

            <!-- Home -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            <!-- ================= Maklumat Kakitangan ================= -->
            <div x-data="{ open:false }" class="px-4">
                <button @click="open=!open"
                    class="flex justify-between w-full py-2 text-sm font-semibold text-gray-700">
                    Maklumat Kakitangan
                    <span x-text="open ? '−' : '+'"></span>
                </button>

                <div x-show="open" class="pl-4 space-y-1" style="display: none;">

                    @if(auth()->user()->level == 1 || auth()->user()->level == 2 || auth()->user()->level == 3)
                        <a href="{{ route('kakitangan.carian') }}" class="block py-1 text-sm">
                            Maklumat Kakitangan
                        </a>
                    @endif

                    @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                        <a href="{{ route('kakitangan.create') }}" class="block py-1 text-sm">
                            Tambah Maklumat
                        </a>
                    @endif

                    <a href="{{ route('surat.index') }}" class="block py-1 text-sm">
                        Surat Pengesahan
                    </a>

                    <a href="{{ route('gaji.index') }}" class="block py-1 text-sm">
                        Maklumat Gaji
                    </a>

                    <a href="{{ route('keluarga.index') }}" class="block py-1 text-sm">
                        Keluarga
                    </a>

                    <a href="{{ route('surat_akuan.index') }}" class="block py-1 text-sm">
                        Surat Akuan Perubatan
                    </a>

                    <a href="{{ route('latihan.index') }}" class="block py-1 text-sm">
                        Latihan
                    </a>
                </div>
            </div>

            <!-- ================= Laporan ================= -->
            <div x-data="{ open:false }" class="px-4">
                <button @click="open=!open"
                    class="flex justify-between w-full py-2 text-sm font-semibold text-gray-700">
                    Laporan
                    <span x-text="open ? '−' : '+'"></span>
                </button>

                <div x-show="open" class="pl-4 space-y-1" style="display: none;">
                    <a href="{{ route('laporan.pengisian_gred') }}" class="block py-1 text-sm">
                        Pengisian Jawatan Mengikut Gred
                    </a>
                    <a href="{{ route('laporan.teknikal') }}" class="block py-1 text-sm">
                        Pengisian Jawatan Teknikal
                    </a>
                    <a href="{{ route('laporan.lantikan') }}" class="block py-1 text-sm">
                        Maklumat Lantikan Penyandang
                    </a>
                    <a href="{{ route('laporan.senarai_perjawatan') }}" class="block py-1 text-sm">
                        Senarai Nama & Perjawatan
                    </a>
                    <a href="{{ route('laporan.penempatan') }}" class="block py-1 text-sm">
                        Penempatan Kakitangan
                    </a>
                    <a href="{{ route('laporan.luar_ciast') }}" class="block py-1 text-sm">
                        Kakitangan Luar CIAST
                    </a>
                    <a href="{{ route('laporan.sambilan') }}" class="block py-1 text-sm">
                        Kakitangan Sambilan
                    </a>
                    <a href="{{ route('laporan.statistik') }}" class="block py-1 text-sm">
                        Statistik Kakitangan
                    </a>
                    <a href="{{ route('laporan.bersara') }}" class="block py-1 text-sm">
                        Kakitangan Bersara
                    </a>
                    <a href="{{ route('laporan.baru') }}" class="block py-1 text-sm">
                        Kakitangan Baru
                    </a>
                    <a href="{{ route('laporan.bertukar') }}" class="block py-1 text-sm">
                        Kakitangan Bertukar
                    </a>
                    <a href="{{ route('laporan.apc_pingat') }}" class="block py-1 text-sm">
                        Senarai APC & Pingat
                    </a>

                    <hr class="my-2">

                    <p class="text-xs font-semibold text-gray-500">Latihan</p>
                    <a href="{{ route('latihan.senarai') }}" class="block py-1 text-sm">
                        Senarai Latihan
                    </a>
                </div>
            </div>

            <!-- ================= Pentadbir Sistem ================= -->
            @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                <div x-data="{ open:false }" class="px-4">
                    <button @click="open=!open"
                        class="flex justify-between w-full py-2 text-sm font-semibold text-gray-700">
                        Pentadbir Sistem
                        <span x-text="open ? '−' : '+'"></span>
                    </button>

                    <div x-show="open" class="pl-4 space-y-1" style="display: none;">
                        <a href="{{ route('pentadbir.peringkat_sumbangan') }}" class="block py-1 text-sm">Peringkat
                            Sumbangan</a>
                        <a href="{{ route('pentadbir.program') }}" class="block py-1 text-sm">Program</a>
                        <a href="{{ route('pentadbir.unit') }}" class="block py-1 text-sm">Unit</a>
                        <a href="{{ route('pentadbir.jenis_isytihar') }}" class="block py-1 text-sm">Jenis Isytihar
                            Harta</a>
                        <a href="{{ route('pentadbir.jenis_penempatan') }}" class="block py-1 text-sm">Jenis Penempatan</a>
                        <a href="{{ route('pentadbir.jawatan') }}" class="block py-1 text-sm">Jawatan</a>
                        <a href="{{ route('pentadbir.gred') }}" class="block py-1 text-sm">Gred</a>
                        <a href="{{ route('pentadbir.perjawatan') }}" class="block py-1 text-sm">Perjawatan</a>
                        <a href="{{ route('pentadbir.elaun') }}" class="block py-1 text-sm">Elaun</a>
                        <a href="{{ route('pentadbir.moto_hari_pekerja') }}" class="block py-1 text-sm">Moto Hari
                            Pekerja</a>

                        <hr class="my-2">

                        <p class="text-xs font-semibold text-gray-500">Surat Pengesahan</p>
                        <a href="{{ route('pentadbir.surat_pengesahan_cari') }}" class="block py-1 text-sm">Cari Pemohon</a>
                        <a href="{{ route('pentadbir.surat_pengesahan_pelulus') }}" class="block py-1 text-sm">Pelulus</a>

                        <p class="text-xs font-semibold text-gray-500 mt-2">Surat Akuan Perubatan</p>
                        <a href="{{ route('pentadbir.surat_akuan_senarai') }}" class="block py-1 text-sm">Senarai
                            Pemohon</a>
                        <a href="{{ route('pentadbir.surat_akuan_pelulus') }}" class="block py-1 text-sm">Pelulus</a>
                    </div>
                </div>
            @endif

            <!-- ================= Bantuan ================= -->
            <div x-data="{ open:false }" class="px-4">
                <button @click="open=!open"
                    class="flex justify-between w-full py-2 text-sm font-semibold text-gray-700">
                    Bantuan
                    <span x-text="open ? '−' : '+'"></span>
                </button>

                <div x-show="open" class="pl-4 space-y-1" style="display: none;">
                    <a href="{{ route('bantuan.tentang') }}" class="block py-1 text-sm">
                        Tentang ePerjawatan
                    </a>

                    @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                        <a href="{{ route('bantuan.manual') }}" class="block py-1 text-sm">
                            Manual Pengguna
                        </a>
                    @endif

                    <p class="text-xs font-semibold text-gray-500 mt-2">Panduan Surat</p>
                    <a href="{{ route('bantuan.manual_permohonan_surat_pengesahan') }}" class="block py-1 text-sm">
                        Surat Pengesahan – Permohonan
                    </a>
                    <a href="{{ route('bantuan.manual_pelulus_surat_pengesahan') }}" class="block py-1 text-sm">
                        Surat Pengesahan – Pelulus
                    </a>
                    <a href="{{ route('bantuan.manual_surat_akuan_perubatan') }}" class="block py-1 text-sm">
                        Surat Akuan Perubatan
                    </a>
                </div>
            </div>

        </div>

        <!-- User -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800">{{ Auth::user()->nama }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="url('/kakitangan')">Profil</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('password.edit')">Tukar Kata Laluan</x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>

    </div>


</nav>