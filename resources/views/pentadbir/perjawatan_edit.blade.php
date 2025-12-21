<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem - Kemaskini Perjawatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Form Section -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kemaskini Perjawatan</h3>
                        <form action="{{ route('pentadbir.perjawatan.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="jawatan" :value="__('Jawatan')" />
                                    <select name="jawatan" id="jawatan" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1">
                                        <option value="">-- Pilih Jawatan --</option>
                                        @foreach($jawatan_list as $j)
                                            <option value="{{ $j->id }}" {{ old('jawatan', $item->jawatan) == $j->id ? 'selected' : '' }}>
                                                {{ $j->jawatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('jawatan')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="gred" :value="__('Gred')" />
                                    <select name="gred" id="gred" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1">
                                        <option value="">-- Pilih Gred --</option>
                                        @foreach($gred_list as $g)
                                            <option value="{{ $g->id }}" {{ old('gred', $item->gred) == $g->id ? 'selected' : '' }}>
                                                {{ $g->gred }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('gred')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="bilanganperjawatan" :value="__('Bilangan Perjawatan')" />
                                    <x-text-input id="bilanganperjawatan" class="block mt-1 w-full" type="number"
                                        name="bilanganperjawatan" :value="old('bilanganperjawatan', $item->bilanganperjawatan)" required autofocus />
                                    <x-input-error :messages="$errors->get('bilanganperjawatan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="waran" :value="__('Kod Waran')" />
                                    <x-text-input id="waran" class="block mt-1 w-full" type="text" name="waran"
                                        :value="old('waran', $item->waran)" required autofocus />
                                    <x-input-error :messages="$errors->get('waran')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('pentadbir.perjawatan') }}"
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