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
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">📖 Tentang ePerjawatan</h1>
                        <p class="text-gray-600">Sistem Pengurusan Maklumat Perjawatan</p>
                    </div>

                    <div class="prose max-w-none">
                        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 mb-6 rounded-r-lg">
                            <h2 class="text-xl font-semibold text-indigo-900 mb-3">Apa itu ePerjawatan?</h2>
                            <p class="text-gray-700 leading-relaxed">
                                ePerjawatan adalah sistem pengurusan maklumat perjawatan yang dibangunkan untuk
                                memudahkan pengurusan data kakitangan, jawatan, latihan, dan pelbagai aspek
                                pengurusan sumber manusia di organisasi.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="text-2xl mr-2">👥</span> Pengurusan Kakitangan
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Sistem pengurusan maklumat kakitangan yang lengkap termasuk biodata,
                                    perjawatan, dan sejarah perkhidmatan.
                                </p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="text-2xl mr-2">📊</span> Laporan & Analisis
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Penjana laporan automatik untuk pelbagai keperluan pengurusan dan
                                    analisis data kakitangan.
                                </p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="text-2xl mr-2">📚</span> Pengurusan Latihan
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Rekod dan pengurusan latihan kakitangan untuk pembangunan profesional
                                    yang berterusan.
                                </p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="text-2xl mr-2">📄</span> Pengurusan Surat
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Sistem permohonan dan pengesahan surat rasmi termasuk surat pengesahan
                                    dan surat akuan perubatan.
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">ℹ️ Maklumat Sistem</h3>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p><strong>Versi:</strong> 2.0 (Laravel)</p>
                                <p><strong>Dibangunkan oleh:</strong> CIAST</p>
                                <p><strong>Sokongan:</strong> Untuk sebarang pertanyaan, sila hubungi pentadbir sistem
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>