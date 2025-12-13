<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan Kakitangan Sambilan') }}
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

                    <p class="mb-4">Laporan dikemaskini sehingga:
                        <strong>{{ $tarikh ? \Carbon\Carbon::parse($tarikh)->format('Y-m-d') : '-' }}</strong></p>

                    <h3 class="mb-2 text-lg font-bold">Ringkasan Penempatan Tenaga Pengajar Contract For Service dan
                        Pekerja Sambilan</h3>
                    <div class="mb-8 overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Jawatan</th>
                                    <th class="p-2 border border-gray-300">Gred</th>
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $jumlahBil = 0; @endphp
                                @foreach($summary as $index => $row)
                                    @php $jumlahBil += $row->bil; @endphp
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->jawatan }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->gred }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->bil }}</td>
                                        <td class="p-2 border border-gray-300"></td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-200">
                                    <td colspan="3" class="p-2 text-right border border-gray-300">JUMLAH KESELURUHAN
                                    </td>
                                    <td class="p-2 text-center border border-gray-300">{{ $jumlahBil }}</td>
                                    <td class="p-2 border border-gray-300"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="mb-2 text-lg font-bold">Penempatan Pekerja Sambilan Harian</h3>
                    <div class="mb-8 overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Nama</th>
                                    <th class="p-2 border border-gray-300">Jawatan</th>
                                    <th class="p-2 border border-gray-300">Gred</th>
                                    <th class="p-2 border border-gray-300">Penempatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sambilan as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->nama }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->jawatan }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->gred }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->unitpenempatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="mb-2 text-lg font-bold">Penempatan Tenaga Pengajar Contract For Service</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Nama</th>
                                    <th class="p-2 border border-gray-300">Jawatan</th>
                                    <th class="p-2 border border-gray-300">Gred</th>
                                    <th class="p-2 border border-gray-300">Penempatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contract as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->nama }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->jawatan }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->gred }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->unit }}</td>
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