<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem - Kemaskini Surat Akuan Senarai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Form Section -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kemaskini Surat Akuan Senarai</h3>
                        <form action="{{ route('pentadbir.surat_akuan_senarai.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6">

                                <div>
                                    <x-input-label for="namakakitangan" :value="__('Nama Kakitangan')" />
                                    <x-text-input id="namakakitangan" class="block mt-1 w-full" type="text" disabled="disabled"
                                        name="namakakitangan" :value="old('namakakitangan', $item->namakakitangan)"
                                        autofocus />
                                    <x-input-error :messages="$errors->get('namakakitangan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pesakit" :value="__('Nama Pesakit')" />
                                    <x-text-input id="pesakit" class="block mt-1 w-full" type="text" name="pesakit"
                                        :value="old('pesakit', $item->pesakit)" autofocus />
                                    <x-input-error :messages="$errors->get('pesakit')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="no_rujukan" :value="__('No Rujukan')" />
                                    <x-text-input id="no_rujukan" class="block mt-1 w-full" type="text"
                                        name="no_rujukan" :value="old('no_rujukan', $item->no_rujukan)" />
                                    <x-input-error :messages="$errors->get('no_rujukan')" class="mt-2" />
                                </div>

                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('pentadbir.surat_akuan_senarai') }}"
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