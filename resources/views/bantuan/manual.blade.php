<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bantuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">📚 Manual Pengguna</h1>
                        <p class="text-gray-600">Panduan penggunaan sistem ePerjawatan</p>
                    </div>

                    <div class="space-y-8">
                        <!-- Tukar Kata Laluan -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-2xl mr-2">🔐</span> Tukar Kata Laluan
                            </h2>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Pilih menu <strong>Pentadbir Sistem</strong>, "Tukar Kata Laluan"</li>
                                <li>Taipkan kata laluan baru di ruangan "Kata laluan baru"</li>
                                <li>Taip sekali lagi kata laluan baru di ruangan "Taip semula kata laluan baru"</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Export ke Excel -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-2xl mr-2">📊</span> Export ke MS Excel
                            </h2>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Tekan kekunci <kbd
                                        class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">CTRL+A</kbd>
                                </li>
                                <li>Tekan kekunci <kbd
                                        class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">CTRL+C</kbd>
                                </li>
                                <li>Buka MS Excel</li>
                                <li>Tekan kekunci <kbd
                                        class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">CTRL+V</kbd>
                                </li>
                            </ol>
                        </div>

                        @if(auth()->user()->level == 1 || auth()->user()->level == 2 || auth()->user()->level == 3)
                            <!-- Cari Maklumat Kakitangan -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">🔍</span> Cari Maklumat Kakitangan
                                </h2>
                                <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                    <li>Pilih menu "Maklumat Kakitangan", "Maklumat Kakitangan"</li>
                                    <li>Masukkan nombor mykad atau nama atau sebahagian nama kakitangan di ruangan yang
                                        disediakan</li>
                                    <li>Klik butang <span
                                            class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Cari</span></li>
                                </ol>
                            </div>

                            <!-- Penetapan Data Asas -->
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-6">
                                <h2 class="text-xl font-semibold text-indigo-900 mb-4">⚙️ Penetapan Data Asas (Base Data)
                                </h2>
                                <p class="text-gray-700 mb-4">
                                    Sistem ePerjawatan ini perlu diisikan dengan data asas. Data asas ini perlu diisi oleh
                                    Pentadbir supaya maklumat lain boleh diisi.
                                </p>
                                <p class="text-gray-700 font-medium mb-2">Data asas yang perlu diisi:</p>
                                <ul class="grid md:grid-cols-2 gap-2 text-gray-700 ml-4">
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Peringkat
                                        Sumbangan</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Program</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Unit</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Jenis Isytihar
                                        Harta</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Jenis
                                        Penempatan</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Jawatan</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Gred</li>
                                    <li class="flex items-center"><span class="text-indigo-600 mr-2">✓</span> Perjawatan
                                    </li>
                                </ul>
                            </div>

                            <!-- Peringkat Sumbangan -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4">📝 Peringkat Sumbangan</h2>

                                <h3 class="text-lg font-medium text-gray-700 mb-3">Tambah Maklumat</h3>
                                <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4 mb-6">
                                    <li>Pilih menu "Pentadbir Sistem", Penetapan, Peringkat Sumbangan</li>
                                    <li>Masukkan peringkat sumbangan di ruangan Peringkat</li>
                                    <li>Klik butang <span
                                            class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                    </li>
                                </ol>

                                <h3 class="text-lg font-medium text-gray-700 mb-3">Edit Peringkat Sumbangan</h3>
                                <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4 mb-6">
                                    <li>Klik <span class="text-indigo-600">Edit</span> pada peringkat sumbangan yang ingin
                                        di edit</li>
                                    <li>Taipkan peringkat yang ingin di edit</li>
                                    <li>Klik butang <span
                                            class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                    </li>
                                </ol>

                                <h3 class="text-lg font-medium text-gray-700 mb-3">Delete Peringkat Sumbangan</h3>
                                <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                    <li>Klik <span class="text-red-600">Delete</span> pada peringkat sumbangan</li>
                                </ol>
                            </div>

                            <!-- Reset Password -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">🔄</span> Reset Password
                                </h2>
                                <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                    <li>Pilih menu Maklumat Kakitangan, Maklumat Kakitangan</li>
                                    <li>Masukkan nama / mykad kakitangan</li>
                                    <li>Tekan butang <span
                                            class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Cari</span></li>
                                    <li>Cari nama kakitangan, pilih tindakan "Reset Password"</li>
                                    <li>Pastikan nama dan mykad kakitangan yang hendak di reset</li>
                                    <li>Klik butang <span
                                            class="px-2 py-1 bg-red-100 text-red-700 rounded text-sm">Reset</span></li>
                                    <li>Password kakitangan akan di set kepada <code
                                            class="px-2 py-1 bg-gray-100 rounded text-sm">'1234'</code></li>
                                </ol>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>