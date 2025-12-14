<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan Pengisian Jawatan Teknikal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-md
           hover:bg-blue-700 focus:outline-none
           focus:ring-2 focus:ring-blue-500">
                            Cetak
                        </button>
                    </div>

                    <p class="mb-4">Laporan dikemaskini sehingga:
                        <strong>{{ $tarikh ? \Carbon\Carbon::parse($tarikh)->format('Y-m-d') : '-' }}</strong>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300" rowspan="2">Bil</th>
                                    <th class="p-2 border border-gray-300" rowspan="2">Gred Jawatan</th>
                                    <th class="p-2 border border-gray-300" rowspan="2">Kod Waran</th>
                                    <th class="p-2 border border-gray-300" rowspan="2">Jawatan</th>
                                    <th class="p-2 border border-gray-300" colspan="3">Bilangan Jawatan</th>
                                </tr>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Lulus</th>
                                    <th class="p-2 border border-gray-300">Isi</th>
                                    <th class="p-2 border border-gray-300">Kosong</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $jumlahlulus = 0;
                                    $jumlahisi = 0;
                                    $jumlahkosong = 0;
                                @endphp
                                @foreach($rows as $index => $row)
                                    @php
                                        $kosong = $row->bilanganperjawatan - $row->isi;
                                        $jumlahlulus += $row->bilanganperjawatan;
                                        $jumlahisi += $row->isi;
                                        $jumlahkosong += $kosong;
                                    @endphp
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->kodgred }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->waran }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->jawatan }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->bilanganperjawatan }}
                                        </td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->isi }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $kosong }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-200">
                                    <td colspan="4" class="p-2 text-right border border-gray-300">Jumlah Keseluruhan
                                    </td>
                                    <td class="p-2 text-center border border-gray-300">{{ $jumlahlulus }}</td>
                                    <td class="p-2 text-center border border-gray-300">{{ $jumlahisi }}</td>
                                    <td class="p-2 text-center border border-gray-300">{{ $jumlahkosong }}</td>
                                </tr>
                                <tr class="font-bold bg-gray-200">
                                    <td colspan="4" class="p-2 text-right border border-gray-300">Peratus Pengisian
                                        Jawatan</td>
                                    <td colspan="3" class="p-2 text-center border border-gray-300">
                                        {{ $jumlahlulus > 0 ? round(($jumlahisi / $jumlahlulus) * 100, 0) . '%' : '0%' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>