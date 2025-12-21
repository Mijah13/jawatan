<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem - Kemaskini Unit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Form Section -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Kemaskini Unit</h3>
                        <form action="{{ route('pentadbir.unit.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <x-input-label for="program" :value="__('Program')" />
                                    <select id="program" name="program"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">-- Sila Pilih Program --</option>
                                        @foreach($program_list as $prog)
                                            <option value="{{ $prog->id }}" {{ old('program', $item->program) == $prog->id ? 'selected' : '' }}>{{ $prog->program }} ({{ $prog->kod }})</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('program')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="unit" :value="__('Nama Unit')" />
                                    <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                                        :value="old('unit', $item->unit)" required />
                                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end gap-2">
                                <a href="{{ route('pentadbir.unit') }}"
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