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
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">📄 Panduan Permohonan Surat Pengesahan</h1>
                        <p class="text-gray-600">Langkah-langkah permohonan Surat Pengesahan dan Butir-Butir
                            Perkhidmatan</p>
                    </div>

                    <!-- Overview -->
                    <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-6 mb-8">
                        <h2 class="text-lg font-semibold text-indigo-900 mb-3">3 Langkah Utama</h2>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                            <li class="font-medium">Muat turun penyata gaji</li>
                            <li class="font-medium">Isi maklumat gaji</li>
                            <li class="font-medium">Buat permohonan Surat Pengesahan</li>
                        </ol>
                    </div>

                    <div class="space-y-8">
                        <!-- Step 1: Muat Turun Penyata Gaji -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-bold mr-3">1</span>
                                <h2 class="text-xl font-semibold text-gray-800">Muat Turun Penyata Gaji</h2>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Layari laman web penyata gaji di <a href="http://www.anm.gov.my/" target="_blank"
                                        class="text-indigo-600 hover:underline">www.anm.gov.my</a></li>
                                <li>Pilih ikon e-penyata gaji</li>
                                <li>Muat turun penyata gaji semasa</li>
                            </ol>
                        </div>

                        <!-- Step 2: Isi Maklumat Gaji -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-bold mr-3">2</span>
                                <h2 class="text-xl font-semibold text-gray-800">Isi Maklumat Gaji</h2>
                            </div>

                            <h3 class="text-lg font-medium text-gray-700 mb-3 mt-6">Tambah Gaji Pokok</h3>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Kemaskini maklumat gaji</strong></li>
                                <li>Pilih menu <span
                                        class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">Tambah</span> pada
                                    kotak Gaji Pokok</li>
                                <li>Masukkan Nombor Gaji dan Gaji Pokok di ruangan yang disediakan</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>

                            <h3 class="text-lg font-medium text-gray-700 mb-3 mt-6">Ubah Maklumat Gaji</h3>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Kemaskini maklumat gaji</strong></li>
                                <li>Klik butang <span class="text-indigo-600">Edit</span> pada kotak Gaji Pokok</li>
                                <li>Masukkan maklumat baru</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>

                            <h3 class="text-lg font-medium text-gray-700 mb-3 mt-6">Tambah Maklumat Elaun</h3>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Kemaskini maklumat gaji</strong></li>
                                <li>Klik menu <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">Tambah
                                        Elaun</span></li>
                                <li>Pilih Jenis Elaun</li>
                                <li>Masukkan Nilai RM</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>

                            <h3 class="text-lg font-medium text-gray-700 mb-3 mt-6">Edit Maklumat Elaun</h3>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Kemaskini maklumat gaji</strong></li>
                                <li>Pilih menu <span class="text-indigo-600">Edit</span> pada kotak elaun</li>
                                <li>Pilih Jenis Elaun</li>
                                <li>Masukkan Nilai RM</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>

                            <h3 class="text-lg font-medium text-gray-700 mb-3 mt-6">Delete Maklumat Elaun</h3>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Kemaskini maklumat gaji</strong></li>
                                <li>Pilih menu <span class="text-red-600">Delete</span> pada elaun yang hendak di delete
                                </li>
                            </ol>
                        </div>

                        <!-- Step 3: Permohonan Surat -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-bold mr-3">3</span>
                                <h2 class="text-xl font-semibold text-gray-800">Permohonan Surat Pengesahan</h2>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Permohonan Baru</strong></li>
                                <li>Masukkan semua maklumat yang diperlukan</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Pengesahan Surat -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-2xl mr-2">✅</span> Pengesahan Surat
                            </h2>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Emel / Telefon / Berjumpa dengan pegawai pengesah</li>
                                <li>Mohon Pegawai Pengesah sahkan surat</li>
                                <li>Ambil surat yang telah ditandatangani dari beliau</li>
                            </ol>
                        </div>

                        <!-- Cetak Surat -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-2xl mr-2">🖨️</span> Cetak Surat Permohonan
                            </h2>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Maklumat Kakitangan</strong>, <strong>Surat Pengesahan</strong>
                                </li>
                                <li>Pilih menu <strong>Cetak</strong></li>
                                <li>Cetak menggunakan browser</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>