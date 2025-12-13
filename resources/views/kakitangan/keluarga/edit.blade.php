<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Kemaskini Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('keluarga.update', $keluarga->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                            <!-- Nama -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nama</label>
                                <input type="text" name="nama"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('nama', $keluarga->nama) }}" required>
                            </div>

                            <!-- Hubungan -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Hubungan</label>
                                <select name="hubungan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    required>
                                    <option value="">Pilih Hubungan</option>
                                    @foreach($hubungan as $h)
                                        <option value="{{ $h->id }}" {{ old('hubungan', $keluarga->hubungan) == $h->id ? 'selected' : '' }}>{{ $h->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 mb-6">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                Kemaskini
                            </button>
                            <a href="{{ route('keluarga.index') }}"
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