<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Pengisian Jawatan Mengikut Gred
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="mb-2 text-lg font-semibold uppercase">
                        Senarai Nama Gagal Isytihar Harta
                    </h3>

                    <hr class="mb-4">

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
                                    <th class="border px-3 py-2 text-center w-12">Bil</th>
                                    <th class="border px-3 py-2 text-left">Nama</th>
                                    <th class="border px-3 py-2 text-left">Jawatan</th>
                                    <th class="border px-3 py-2 text-center w-20">Gred</th>
                                    <th class="border px-3 py-2 text-center w-40">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($rows as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="border px-3 py-2 text-center">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="border px-3 py-2">
                                            {{ $row->nama }}
                                        </td>

                                        <td class="border px-3 py-2">
                                            {{ $row->kod }}
                                        </td>

                                        <td class="border px-3 py-2 text-center">
                                            {{ $row->gred }}
                                        </td>

                                        <td class="border px-3 py-2 text-center">
                                            @if($row->beza != 0)
                                                <span class="inline-block rounded bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">
                                                    Belum Isytihar
                                                </span>
                                            @else
                                                <span class="inline-block rounded bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">
                                                    Lebih 5 Tahun
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                                            Tiada rekod dijumpai
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
