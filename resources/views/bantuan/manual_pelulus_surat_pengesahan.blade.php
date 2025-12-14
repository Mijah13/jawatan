<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bantuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">✍️ Manual Pelulus Surat Pengesahan</h1>
                        <p class="text-gray-600">Panduan untuk Pegawai Pengesah menandatangani Surat Pengesahan dan
                            Butir-Butir Perkhidmatan</p>
                    </div>

                    <div class="space-y-8">
                        <!-- Cari Permohonan -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-bold mr-3">1</span>
                                <h2 class="text-xl font-semibold text-gray-800">Cari Permohonan Surat</h2>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Login ke ePerjawatan</li>
                                <li>Pilih menu <strong>Pentadbir Sistem</strong>, <strong>Surat Pengesahan</strong>,
                                    <strong>Cari Pemohon</strong></li>
                                <li>Taipkan nama pada ruangan yang disediakan</li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Tambah Maklumat -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-bold mr-3">2</span>
                                <h2 class="text-xl font-semibold text-gray-800">Tambah Maklumat pada Surat</h2>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Pilih menu <span class="text-indigo-600">Update</span></li>
                                <li>Masukkan nombor fail peribadi dan "running number" surat</li>
                                <li>Pilih radio button <strong>Tanda Tangan Pegawai</strong></li>
                                <li>Klik butang <span
                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-sm">Submit</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Pamer Surat -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-bold mr-3">3</span>
                                <h2 class="text-xl font-semibold text-gray-800">Pamer dan Cetak Surat</h2>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                                <li>Pilih menu <strong>Surat</strong> pada senarai berkaitan</li>
                                <li>Semak maklumat surat yang dipaparkan</li>
                                <li>Cetak surat menggunakan fungsi cetak browser</li>
                            </ol>
                        </div>

                        <!-- Important Note -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-900 mb-3 flex items-center">
                                <span class="text-2xl mr-2">⚠️</span> Nota Penting
                            </h3>
                            <ul class="space-y-2 text-gray-700 ml-4">
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2 mt-1">•</span>
                                    <span>Pastikan semua maklumat dalam surat adalah tepat sebelum menandatangani</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2 mt-1">•</span>
                                    <span>Nombor fail peribadi dan running number perlu diisi dengan betul</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2 mt-1">•</span>
                                    <span>Surat yang telah ditandatangani akan direkodkan dalam sistem</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>