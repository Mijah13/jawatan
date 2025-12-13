<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Pengisian Jawatan Mengikut Gred
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <p class="mb-4">
                        Laporan dikemaskini sehingga:
                        <strong>{{ $tarikh }}</strong>
                    </p>

                    <table class="w-full border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2 border" rowspan="2">Bil</th>
                                <th class="p-2 border" rowspan="2">Kod Waran</th>
                                <th class="p-2 border" rowspan="2">Jawatan</th>
                                <th class="p-2 border" rowspan="2">Gred</th>
                                <th class="p-2 border" colspan="3">Bilangan Jawatan</th>
                            </tr>
                            <tr class="bg-gray-200">
                                <th class="p-2 border">Lulus</th>
                                <th class="p-2 border">Isi</th>
                                <th class="p-2 border">Kosong</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $bil = 1;
                                $jumlahlulus = 0;
                                $isi = 0;
                                $jumlahkosong = 0;
                            @endphp

                            @foreach ($rows as $row)
                                @php
                                    $kosong = $row->bilanganperjawatan - $row->isi;
                                    $jumlahlulus += $row->bilanganperjawatan;
                                    $isi += $row->isi;
                                    $jumlahkosong += $kosong;
                                @endphp

                                <tr class="{{ $bil % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                    <td class="p-2 border text-center">{{ $bil++ }}</td>
                                    <td class="p-2 border text-center">{{ $row->waran }}</td>
                                    <td class="p-2 border">{{ $row->jawatan }}</td>
                                    <td class="p-2 border text-center">{{ $row->kodgred }}</td>
                                    <td class="p-2 border text-center">{{ $row->bilanganperjawatan }}</td>
                                    <td class="p-2 border text-center">{{ $row->isi }}</td>
                                    <td class="p-2 border text-center">{{ $kosong }}</td>
                                </tr>
                            @endforeach

                            <tr class="font-semibold bg-gray-100">
                                <td colspan="4" class="p-2 border">Jumlah Keseluruhan</td>
                                <td class="p-2 border text-center">{{ $jumlahlulus }}</td>
                                <td class="p-2 border text-center">{{ $isi }}</td>
                                <td class="p-2 border text-center">{{ $jumlahkosong }}</td>
                            </tr>

                            <tr class="font-semibold bg-gray-100">
                                <td colspan="4" class="p-2 border">Peratus Pengisian Jawatan</td>
                                <td colspan="3" class="p-2 border text-center">
                                    {{ $jumlahlulus > 0 ? round(($isi / $jumlahlulus) * 100, 0) : 0 }}%
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