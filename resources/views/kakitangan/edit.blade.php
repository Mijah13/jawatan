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


                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            <span class="font-medium">Berjaya!</span> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ url('kakitangan_update/' . $row->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <table class="w-full table-auto">
                            <tr>
                                <td class="w-1/5 bg-yellow-100">Nama</td>
                                <td>
                                    <input type="text" name="nama" value="{{ $row->nama }}" class="w-full p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>MyKAD</td>
                                <td>
                                    <input type="text" name="mykad" maxlength="12" value="{{ $row->mykad }}"
                                        class="p-2 border">
                                    <span class="text-gray-500">tanpa tanda "-"</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Lahir</td>
                                <td>
                                    <input type="date" name="tarikhlahir" value="{{ $row->tarikhlahir }}"
                                        class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>No Fail Peribadi</td>
                                <td>
                                    <input type="text" name="nofailperibadi" value="{{ $row->nofailperibadi }}"
                                        class="w-full p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Jawatan</td>
                                <td>
                                    <select name="jawatan" class="w-full p-2 border">
                                        @foreach($jawatan as $j)
                                            <option value="{{ $j->id }}" {{ $j->id == $row->jawatan ? 'selected' : '' }}>
                                                {{ $j->jwt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Gred</td>
                                <td>
                                    <select name="gred" class="w-full p-2 border">
                                        @foreach($gred as $g)
                                            <option value="{{ $g->id }}" {{ $g->id == $row->gred ? 'selected' : '' }}>
                                                {{ $g->gred }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>No Waran</td>
                                <td>
                                    <input type="text" name="nowaran" value="{{ $row->nowaran }}"
                                        class="w-full p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Penempatan Waran</td>
                                <td>
                                    <select name="penempatanwaran" class="w-full p-2 border">
                                        @foreach($tmpwrn as $t)
                                            <option value="{{ $t->id }}" {{ $t->id == $row->penempatanwaran ? 'selected' : '' }}>
                                                {{ $t->program }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Penempatan Operasi</td>
                                <td>
                                    <select name="penempatanoperasi" class="w-full p-2 border">
                                        @foreach($operasi as $o)
                                            <option value="{{ $o->id }}" {{ $o->id == $row->penempatanoperasi ? 'selected' : '' }}>
                                                {{ $o->program }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Unit</td>
                                <td>
                                    <select name="unit" class="w-full p-2 border">
                                        @foreach($unit as $u)
                                            <option value="{{ $u->id }}" {{ $u->id == $row->unit ? 'selected' : '' }}>
                                                {{ $u->kodprg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Lantikan Pertama</td>
                                <td>
                                    <input type="date" name="tarikhlantikanpertama"
                                        value="{{ $row->tarikhlantikanpertama }}" class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Lantikan Sekarang</td>
                                <td>
                                    <input type="date" name="tarikhlantikansekarang"
                                        value="{{ $row->tarikhlantikansekarang }}" class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Pengesahan Jawatan</td>
                                <td>
                                    <input type="date" name="tarikhpengesahanjawatan"
                                        value="{{ $row->tarikhpengesahanjawatan }}" class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Memangku</td>
                                <td>
                                    <input type="date" name="tarikhmemangku" value="{{ $row->tarikhmemangku }}"
                                        class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Naik Pangkat</td>
                                <td>
                                    <input type="date" name="tarikhnaikpangkat" value="{{ $row->tarikhnaikpangkat }}"
                                        class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Ke CIAS</td>
                                <td>
                                    <input type="date" name="tarikhkeciast" value="{{ $row->tarikhkeciast }}"
                                        class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Tarikh Bertukar Keluar</td>
                                <td>
                                    <input type="date" name="tarikhbertukarkeluar"
                                        value="{{ $row->tarikhbertukarkeluar }}" class="p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Penempatan Baru</td>
                                <td>
                                    <input type="text" name="penempatanbaru" value="{{ $row->penempatanbaru }}"
                                        class="w-full p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>HRMIS Kemaskini</td>
                                <td>
                                    <input type="text" name="hrmiskemaskini" value="{{ $row->hrmiskemaskini }}"
                                        class="w-full p-2 border">
                                </td>
                            </tr>

                            <tr>
                                <td>Kod Penempatan</td>
                                <td>
                                    <select name="kodpenempatan" class="w-full p-2 border">
                                        @foreach($kodtmpt as $k)
                                            <option value="{{ $k->id }}" {{ $k->id == $row->kodpenempatan ? 'selected' : '' }}>
                                                {{ $k->penempatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Level</td>
                                <td>
                                    <select name="level" class="w-full p-2 border">
                                        @foreach($level as $l)
                                            <option value="{{ $l->level }}" {{ $l->level == $row->level ? 'selected' : '' }}>
                                                {{ $l->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Emel</td>
                                <td>
                                    <input type="text" name="emel" value="{{ $row->emel }}" class="w-full p-2 border">
                                </td>
                            </tr>

                        </table>

                        <button type="submit" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">Simpan</button>

                    </form>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>