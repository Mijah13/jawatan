<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan Senarai Pegawai Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <button onclick="window.print()"
                            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Cetak
                        </button>
                    </div>

                    <h3 class="mb-4 text-lg font-bold">Senarai Pegawai Baru</h3>
                    <p class="mb-4">Pegawai yang lapor diri di CIAST kurang dari 6 bulan.</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Nama</th>
                                    <th class="p-2 border border-gray-300">Jawatan</th>
                                    <th class="p-2 border border-gray-300">Gred</th>
                                    <th class="p-2 border border-gray-300">Program</th>
                                    <th class="p-2 border border-gray-300">Unit</th>
                                    <th class="p-2 border border-gray-300">Tarikh Ke CIAST</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->nama }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->jawatan }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->gred }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->program }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->unit }}</td>
                                        <td class="p-2 text-center border border-gray-300">
                                            {{ $row->tarikhkeciast ? \Carbon\Carbon::parse($row->tarikhkeciast)->format('d-m-Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>