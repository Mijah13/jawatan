<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Senarai Nama & Perjawatan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="mb-4 text-center font-bold uppercase">
                        Senarai Nama dan Perjawatan <br>
                        di Pusat Latihan Pengajar dan Kemahiran Lanjutan (CIAST) <br>
                        pada {{ now()->format('d-m-Y') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border">Bil</th>
                                    <th class="p-2 border">Kod Waran</th>
                                    <th class="p-2 border">Jawatan</th>
                                    <th class="p-2 border">Gred</th>
                                    <th class="p-2 border">Lulus</th>
                                    <th class="p-2 border">Isi</th>
                                    <th class="p-2 border">Program</th>
                                    <th class="p-2 border">Bil</th>
                                    <th class="p-2 border">Nama</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $bil = 1; @endphp

                                @foreach ($perjawatan as $pj)
                                    @php
                                        $staff = $kakitanganByGred[$pj->idgred] ?? collect();
                                        $rowspan = max($staff->count(), 1);
                                    @endphp

                                    <tr>
                                        <td class="p-2 border text-center" rowspan="{{ $rowspan }}">
                                            {{ $bil++ }}
                                        </td>
                                        <td class="p-2 border text-center" rowspan="{{ $rowspan }}">
                                            {{ $pj->waran }}
                                        </td>
                                        <td class="p-2 border" rowspan="{{ $rowspan }}">
                                            {{ $pj->jawatan }}
                                        </td>
                                        <td class="p-2 border text-center" rowspan="{{ $rowspan }}">
                                            {{ $pj->gred }}
                                        </td>
                                        <td class="p-2 border text-center" rowspan="{{ $rowspan }}">
                                            {{ $pj->lulus }}
                                        </td>
                                        <td class="p-2 border text-center" rowspan="{{ $rowspan }}">
                                            {{ $staff->count() }}
                                        </td>

                                        @if ($staff->count())
                                            <td class="p-2 border text-center">
                                                {{ $staff[0]->kod }}
                                            </td>
                                            <td class="p-2 border text-center">1</td>
                                            <td class="p-2 border">
                                                {{ $staff[0]->nama }}
                                            </td>
                                        @else
                                            <td class="p-2 border"></td>
                                            <td class="p-2 border"></td>
                                            <td class="p-2 border"></td>
                                        @endif
                                    </tr>

                                    @foreach ($staff->slice(1) as $index => $s)
                                        <tr>
                                            <td class="p-2 border text-center">{{ $s->kod }}</td>
                                            <td class="p-2 border text-center">{{ $index + 2 }}</td>
                                            <td class="p-2 border">{{ $s->nama }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>