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
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">🏥 Panduan Surat Akuan Perubatan</h1>
                        <p class="text-gray-600">Maklumat dan panduan untuk Surat Akuan Perubatan</p>
                    </div>

                    <!-- Overview -->
                    <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-6 mb-8">
                        <h2 class="text-lg font-semibold text-indigo-900 mb-3">Tentang Surat Akuan Perubatan</h2>
                        <p class="text-gray-700">
                            Surat Akuan Perubatan adalah dokumen rasmi yang diperlukan untuk tujuan perubatan dan
                            pentadbiran.
                            Surat ini perlu diisi dengan maklumat yang tepat dan lengkap.
                        </p>
                    </div>

                    <!-- Sample Image Section -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="text-2xl mr-2">📋</span> Contoh Surat Akuan Perubatan
                        </h2>
                        <div class="bg-gray-100 rounded-lg p-8 text-center">
                            <div class="max-w-2xl mx-auto">
                                <img src="/GAMBAR/bantuan_surat_akuan_hospital.png" alt="Contoh Surat Akuan Perubatan"
                                    class="w-full h-auto rounded-lg shadow-lg border border-gray-300"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display:none;" class="bg-gray-200 rounded-lg p-12 text-gray-500">
                                    <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p>Imej contoh surat tidak tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="space-y-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-2xl mr-2">📝</span> Maklumat Yang Diperlukan
                            </h3>
                            <ul class="space-y-2 text-gray-700 ml-4">
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2 mt-1">✓</span>
                                    <span>Nama kakitangan</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2 mt-1">✓</span>
                                    <span>Nama pesakit (jika berbeza dari kakitangan)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2 mt-1">✓</span>
                                    <span>Nama hospital</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2 mt-1">✓</span>
                                    <span>Nombor rujukan hospital</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-indigo-600 mr-2 mt-1">✓</span>
                                    <span>Wad (jika berkenaan)</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-900 mb-3 flex items-center">
                                <span class="text-2xl mr-2">⚠️</span> Nota Penting
                            </h3>
                            <ul class="space-y-2 text-gray-700 ml-4">
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2 mt-1">•</span>
                                    <span>Pastikan semua maklumat yang diisi adalah tepat dan lengkap</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2 mt-1">•</span>
                                    <span>Surat akuan perubatan perlu disahkan oleh pegawai yang bertanggungjawab</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-600 mr-2 mt-1">•</span>
                                    <span>Simpan salinan surat untuk rekod peribadi</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="text-2xl mr-2">📞</span> Bantuan Lanjut
                            </h3>
                            <p class="text-gray-700">
                                Untuk sebarang pertanyaan atau bantuan berkaitan Surat Akuan Perubatan,
                                sila hubungi Unit Sumber Manusia atau pegawai yang bertanggungjawab.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>