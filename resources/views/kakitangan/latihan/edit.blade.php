<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah Latihan Kakitangan') }}
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

                    <form method="POST" action="{{ route('latihan.update', $latihan->id) }}"
                        onsubmit="return confirm('Adakah anda ingin kemaskini data ini?');">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">

                            <!-- Tajuk -->
                            <div class="md:col-span-2">
                                <label class="block mb-2 font-bold text-gray-700">Tajuk</label>
                                <input type="text" name="tajuk"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $latihan->tajuk }}">
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Kategori</label>
                                <select name="kategori"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    >
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}" {{ $latihan->kategori == $k->id ? 'selected' : '' }}>
                                            {{ $k->kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jenis -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Jenis</label>
                                <select name="jenis"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    >
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jenis as $j)
                                        <option value="{{ $j->id }}" {{ $latihan->jenis == $j->id ? 'selected' : '' }}>
                                            {{ $j->jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tarikh Mula -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Mula</label>
                                <input type="date" name="mula"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $latihan->mula ? $latihan->mula->format('Y-m-d') : '' }}">
                            </div>

                            <!-- Tarikh Tamat -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tarikh Tamat</label>
                                <input type="date" name="tamat"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $latihan->tamat ? $latihan->tamat->format('Y-m-d') : '' }}">
                            </div>

                            <!-- Tempoh -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tempoh (Hari)</label>
                                <input type="number" name="tempoh"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $latihan->tempoh }}">
                            </div>

                            <!-- Tempat -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Tempat</label>
                                <input type="text" name="tempat"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $latihan->tempat }}">
                            </div>

                            <!-- Penganjur -->
                            <div class="md:col-span-2">
                                <label class="block mb-2 font-bold text-gray-700">Penganjur</label>
                                <input type="text" name="penganjur"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $latihan->penganjur }}">
                            </div>

                        </div>

                        <div class="flex gap-2 mb-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
           hover:bg-blue-700 focus:outline-none
           focus:ring-2 focus:ring-blue-500">
                                Update
                            </button>
                            <a href="{{ route('latihan.index') }}"
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