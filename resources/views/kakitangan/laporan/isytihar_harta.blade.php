<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Pengisytiharan Harta Mengikut Gred
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="mb-2 text-lg font-semibold uppercase">
                        Pengisytiharan Harta Mengikut Gred
                    </h3>

                    <hr class="mb-4">

                    <p class="mb-1 text-sm text-gray-600">
                        A: Waran CIAST di CIAST &nbsp;|&nbsp; B: Waran CIAST di Luar
                    </p>

                    <p class="mb-6 text-sm text-gray-600">
                        Laporan dikemaskini sehingga:
                        <span class="font-semibold text-gray-900">
                            {{ $tarikhKemaskini }}
                        </span>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-2 py-2 text-center w-12">Bil</th>
                                    <th class="border px-2 py-2 text-center w-20">Gred</th>
                                    <th class="border px-3 py-2 text-left">Jawatan</th>
                                    <th class="border px-2 py-2 text-center w-20">Kod</th>
                                    <th class="border px-2 py-2 text-center w-24">Perjawatan</th>
                                    <th class="border px-2 py-2 text-center w-28">Kurang 5 Tahun</th>
                                    <th class="border px-2 py-2 text-center w-16">%</th>
                                    <th class="border px-2 py-2 text-center w-28">Lebih 5 Tahun</th>
                                    <th class="border px-2 py-2 text-center w-16">%</th>
                                    <th class="border px-2 py-2 text-center w-28">Tidak Isytihar</th>
                                    <th class="border px-2 py-2 text-center w-16">%</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($rows as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="border px-2 py-2 text-center">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->nogred }}
                                        </td>

                                        <td class="border px-3 py-2">
                                            {{ $row->jawatan2 }}
                                        </td>

                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->kod }}
                                        </td>

                                        <td class="border px-2 py-2 text-center font-semibold">
                                            {{ $row->bilanganperjawatan }}
                                        </td>

                                        {{-- Kurang 5 Tahun --}}
                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->kurang5thn }}
                                        </td>

                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->bilanganperjawatan != 0
                                                ? round(($row->kurang5thn / $row->bilanganperjawatan) * 100)
                                                : 0 }}%
                                        </td>

                                        {{-- Lebih 5 Tahun --}}
                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->{'5thndanlebih'} }}
                                        </td>

                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->bilanganperjawatan != 0
                                                ? round(($row->{'5thndanlebih'} / $row->bilanganperjawatan) * 100)
                                                : 0 }}%
                                        </td>

                                        {{-- Tidak Isytihar --}}
                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->tidakisytihar }}
                                        </td>

                                        <td class="border px-2 py-2 text-center">
                                            {{ $row->bilanganperjawatan != 0
                                                ? round(($row->tidakisytihar / $row->bilanganperjawatan) * 100)
                                                : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-4 py-4 text-center text-gray-500">
                                            Tiada data dijumpai
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
