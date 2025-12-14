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
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">💡 Cadangan & Maklum Balas</h1>
                        <p class="text-gray-600">Bantu kami memperbaiki sistem ePerjawatan</p>
                    </div>

                    <div class="prose max-w-none">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-6 rounded-r-lg">
                            <p class="text-gray-700 leading-relaxed">
                                Kami sentiasa berusaha untuk meningkatkan kualiti sistem ePerjawatan.
                                Cadangan dan maklum balas anda amat dihargai untuk membantu kami
                                menyediakan perkhidmatan yang lebih baik.
                            </p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📝 Hantar Cadangan Anda</h3>
                            <form class="space-y-4">
                                <div>
                                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kategori
                                    </label>
                                    <select id="kategori" name="kategori"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Pilih Kategori</option>
                                        <option value="penambahbaikan">Penambahbaikan Sistem</option>
                                        <option value="masalah">Laporan Masalah</option>
                                        <option value="ciri_baru">Cadangan Ciri Baru</option>
                                        <option value="lain">Lain-lain</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="cadangan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cadangan / Maklum Balas
                                    </label>
                                    <textarea id="cadangan" name="cadangan" rows="6"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Sila nyatakan cadangan atau maklum balas anda di sini..."></textarea>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Hantar Cadangan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">📞 Hubungi Kami</h3>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p><strong>Email:</strong> support@ciast.gov.my</p>
                                <p><strong>Telefon:</strong> 03-XXXX XXXX</p>
                                <p><strong>Waktu Operasi:</strong> Isnin - Jumaat, 8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>