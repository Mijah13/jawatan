<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah Kakitangan') }}
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

                    <form method="POST" action="{{ route('kakitangan.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            <!-- Nama -->
                            <div class="col-span-2">
                                <label class="block mb-2 font-bold text-gray-700">Nama</label>
                                <input type="text" name="nama"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('nama') }}" required>
                            </div>

                            <!-- MyKad -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">No. MyKad</label>
                                <input type="text" name="mykad"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('mykad') }}" required>
                                <p class="text-xs text-gray-500">Tanpa tanda "-"</p>
                            </div>

                            <!-- Tarikh Lahir -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Lahir</label>
                                <input type="date" name="tarikhlahir"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhlahir') }}">
                            </div>

                            <!-- Kata Laluan -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Kata Laluan</label>
                                <input type="password" name="katalaluan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Level Pengguna -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Level Pengguna</label>
                                <select name="level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Pilih Level</option>
                                    @foreach($level as $lvl)
                                        <option value="{{ $lvl->level }}" {{ old('level') == $lvl->level ? 'selected' : '' }}>
                                            {{ $lvl->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Emel -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">E-mel CIAST</label>
                                <div class="flex items-center">
                                    <input type="text" name="emel"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:border-blue-500"
                                        value="{{ old('emel') }}">
                                    <span
                                        class="px-3 py-2 text-gray-600 bg-gray-200 border border-l-0 border-gray-300 rounded-r-lg">@ciast.gov.my</span>
                                </div>
                            </div>

                            <!-- No Fail Peribadi -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nombor Fail Peribadi</label>
                                <input type="text" name="nofailperibadi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('nofailperibadi') }}">
                            </div>

                            <!-- Jawatan -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Jawatan</label>
                                <select name="jawatan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Pilih Jawatan</option>
                                    @foreach($jawatan as $jwt)
                                        <option value="{{ $jwt->id }}" {{ old('jawatan') == $jwt->id ? 'selected' : '' }}>
                                            {{ $jwt->jwt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Gred -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Gred</label>
                                <select name="gred"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Pilih Gred</option>
                                    @foreach($gred as $grd)
                                        <option value="{{ $grd->id }}" {{ old('gred') == $grd->id ? 'selected' : '' }}>
                                            {{ $grd->gred }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nombor Waran -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nombor Waran</label>
                                <input type="text" name="nowaran"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('nowaran') }}">
                            </div>

                            <!-- Penempatan Waran -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Penempatan Mengikut Waran</label>
                                <select name="penempatanwaran"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Program/Bahagian</option>
                                    @foreach($tmpwrn as $prog)
                                        <option value="{{ $prog->id }}" {{ old('penempatanwaran') == $prog->id ? 'selected' : '' }}>{{ $prog->program }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Penempatan Operasi -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Penempatan Operasi</label>
                                <select name="penempatanoperasi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Penempatan Operasi</option>
                                    @foreach($operasi as $op)
                                        <option value="{{ $op->id }}" {{ old('penempatanoperasi') == $op->id ? 'selected' : '' }}>{{ $op->program }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Unit</label>
                                <select name="unit"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Unit</option>
                                    @foreach($unit as $unt)
                                        <option value="{{ $unt->id }}" {{ old('unit') == $unt->id ? 'selected' : '' }}>
                                            {{ $unt->kodprg }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jenis Penempatan -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Jenis Penempatan</label>
                                <select name="kodpenempatan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Kod Penempatan</option>
                                    @foreach($kodtmpt as $kt)
                                        <option value="{{ $kt->id }}" {{ old('kodpenempatan') == $kt->id ? 'selected' : '' }}>
                                            {{ $kt->penempatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tarikh Lantikan Pertama -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Lantikan Pertama</label>
                                <input type="date" name="tarikhlantikanpertama"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhlantikanpertama') }}">
                            </div>

                            <!-- Tarikh Lantikan Semasa -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Lantikan Ke Jawatan
                                    Sekarang</label>
                                <input type="date" name="tarikhlantikansekarang"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhlantikansekarang') }}">
                            </div>

                            <!-- Tarikh Pengesahan -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Pengesahan Jawatan</label>
                                <input type="date" name="tarikhpengesahanjawatan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhpengesahanjawatan') }}">
                            </div>

                            <!-- Tarikh Memangku -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Memangku</label>
                                <input type="date" name="tarikhmemangku"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhmemangku') }}">
                            </div>

                            <!-- Tarikh Naik Pangkat -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Naik Pangkat</label>
                                <input type="date" name="tarikhnaikpangkat"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhnaikpangkat') }}">
                            </div>

                            <!-- Tarikh Ke CIAST -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Ke CIAST</label>
                                <input type="date" name="tarikhkeciast"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhkeciast') }}">
                            </div>

                            <!-- Tarikh Bertukar -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Bertukar Dari CIAST</label>
                                <input type="date" name="tarikhbertukarkeluar"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('tarikhbertukarkeluar') }}">
                            </div>

                            <!-- Penempatan Baru -->
                            <div class="col-span-2">
                                <label class="block mb-2 font-bold text-gray-700">Penempatan Baru</label>
                                <input type="text" name="penempatanbaru"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('penempatanbaru') }}">
                            </div>

                            <!-- HRMIS Kemaskini -->
                            <div class="col-span-2">
                                <label class="block mb-2 font-bold text-gray-700">HRMIS Telah Dikemaskini</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="hrmiskemaskini" value="1" class="form-radio" {{ old('hrmiskemaskini') == '1' ? 'checked' : '' }}>
                                        <span class="ml-2">Sudah</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="hrmiskemaskini" value="0" class="form-radio" {{ old('hrmiskemaskini') == '0' ? 'checked' : 'checked' }}>
                                        <span class="ml-2">Belum</span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="flex gap-2 mt-6">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                Simpan
                            </button>
                            <a href="{{ route('kakitangan.index') }}"
                                class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>