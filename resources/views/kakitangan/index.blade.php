<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Maklumat Peribadi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="w-full border border-collapse border-gray-300">
                        <tbody>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Nama</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->nama }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">MyKAD</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->mykad }}</td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Tarikh Lahir</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->tarikhlahir }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Nombor Fail Peribadi</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->nofailperibadi }}</td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Jawatan</td>
                                <td class="p-2 border border-gray-300">
                                    {{ $kakitangan->jawatanRelation->kod ?? '' }} -
                                    {{ $kakitangan->jawatanRelation->jawatan ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Gred</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->gredRelation->gred ?? '' }}</td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Nombor Waran</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->nowaran }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Penempatan Mengikut Waran</td>
                                <td class="p-2 border border-gray-300">
                                    {{ $kakitangan->penempatanWaranRelation->kod ?? '' }} -
                                    {{ $kakitangan->penempatanWaranRelation->program ?? '' }}
                                </td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Penempatan Operasi</td>
                                <td class="p-2 border border-gray-300">
                                    {{ $kakitangan->penempatanOperasiRelation->kod ?? '' }} -
                                    {{ $kakitangan->penempatanOperasiRelation->program ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Unit</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->unitRelation->unit ?? '' }}</td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Kod Penempatan</td>
                                <td class="p-2 border border-gray-300">
                                    {{ $kakitangan->kodPenempatanRelation->kod ?? '' }} -
                                    {{ $kakitangan->jenisPenempatanRelation->jenis ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Tarikh Lantikan Pertama</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->tarikhlantikanpertama }}</td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Tarikh Lantikan Ke Jawatan Sekarang
                                </td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->tarikhlantikansekarang }}</td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 border border-gray-300">Tarikh Lantikan Ke Jawatan Sekarang</td>
                                <td class="p-2 font-bold border border-gray-300">
                                    {{ $kakitangan->tarikhlantikansekarang }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Tarikh Pengesahan Jawatan</td>
                                <td class="p-2 font-bold border border-gray-300">
                                    {{ $kakitangan->tarikhpengesahanjawatan }}
                                </td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 border border-gray-300">Tarikh Memangku</td>
                                <td class="p-2 font-bold border border-gray-300">{{ $kakitangan->tarikhmemangku }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Tarikh Naik Pangkat</td>
                                <td class="p-2 font-bold border border-gray-300">{{ $kakitangan->tarikhnaikpangkat }}
                                </td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 border border-gray-300">Tarikh Ke CIAST</td>
                                <td class="p-2 font-bold border border-gray-300">{{ $kakitangan->tarikhkeciast }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-gray-300">Tarikh Bertukar Dari CIAST</td>
                                <td class="p-2 font-bold border border-gray-300">{{ $kakitangan->tarikhbertukarkeluar }}
                                </td>
                            </tr>
                            <tr class="bg-yellow-100">
                                <td class="p-2 border border-gray-300">Penempatan Baru</td>
                                <td class="p-2 font-bold border border-gray-300">{{ $kakitangan->penempatanbaru }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">HRMIS Sub-Modul Perkhidmatan Telah
                                    Dikemaskini</td>
                                <td class="p-2 border border-gray-300">
                                    {{ $kakitangan->hrmiskemaskini ? 'Sudah Kemaskini' : 'Belum Kemaskini' }}
                                </td>
                            </tr>

                            {{-- Repeat similar structure for harta, apc, pencapaian, pingat --}}
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Isytihar Harta</td>
                                <td class="p-2 border border-gray-300">
                                    @foreach($kakitangan->harta as $h)
                                        {{ $h->tarikhisytihar }}, {{ $h->no_rujukan }}, {{ $h->jenisIsytihar->jenis ?? '' }}
                                        <br>
                                    @endforeach
                                </td>
                            </tr>

                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Anugerah Perkhidmatan Cemerlang</td>
                                <td class="p-2 border border-gray-300">
                                    @foreach($kakitangan->apc as $a)
                                        {{ $a->tahunterima }} <br>
                                    @endforeach
                                </td>
                            </tr>

                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Pencapaian</td>
                                <td class="p-2 border border-gray-300">
                                    @foreach($kakitangan->pencapaian as $p)
                                        {{ $p->pencapaian }}, {{ $p->peringkatSumbangan->peringkat ?? '' }},
                                        {{ $p->tarikhpencapaian }} <br>
                                    @endforeach
                                </td>
                            </tr>

                            <tr>
                                <td class="p-2 font-bold border border-gray-300">Pingat Kebesaran</td>
                                <td class="p-2 border border-gray-300">
                                    @foreach($kakitangan->pingat as $pg)
                                        {{ $pg->pingat }} <br>
                                    @endforeach
                                </td>
                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>