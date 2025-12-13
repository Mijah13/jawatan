<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Kemaskini Taraf Perkhidmatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('taraf.update') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block mb-2 font-bold text-gray-700">Taraf Perkhidmatan</label>
                            <select name="taraf"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                @foreach($taraf as $t)
                                    <option value="{{ $t->id }}" {{ $kakitangan->taraf_perkhidmatan == $t->id ? 'selected' : '' }}>
                                        {{ $t->taraf }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2 mt-6">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                Submit
                            </button>
                            <a href="{{ route('surat.index') }}"
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