<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem - Kemaskini Surat Akuan Pelulus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Form Section -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kemaskini Surat Akuan Pelulus</h3>
                        <form action="{{ route('pentadbir.surat_akuan_pelulus.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <x-input-label for="idkakitangan" :value="__('ID Kakitangan')" />
                                    <select name="idkakitangan" id="idkakitangan" disabled
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1">
                                        <option value="">-- Pilih ID Kakitangan --</option>
                                        @foreach($kakitangan_list as $k)
                                            <option value="{{ $k->id }}" {{ old('idkakitangan', $item->idkakitangan) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('idkakitangan')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="tarikh" :value="__('Tarikh')" />
                                    <x-text-input id="tarikh" class="block mt-1 w-full" type="date" name="tarikh"
                                        :value="old('tarikh', $item->tarikh)" required autofocus />
                                    <x-input-error :messages="$errors->get('tarikh')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('pentadbir.surat_akuan_pelulus') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Batal') }}
                                </a>
                                <x-primary-button>
                                    {{ __('Kemaskini') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>