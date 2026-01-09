<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Maklumat Gaji') }}
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

                    <form method="POST" action="{{ route('elaun.update_elaun', $elaun->id) }}"
                        onsubmit="return confirm('Adakah anda ingin mengedit data ini?');">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">

                            <!-- Gaji Pokok -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Pilih Elaun</label>
                                <select name="elaun" id="elaun"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="">Pilih Elaun</option>
                                    @foreach ($elaunList as $e)
                                        <option value="{{ $e->id }}" {{ $elaun->elaun == $e->id ? 'selected' : '' }}>
                                            {{ $e->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Nombor Gaji -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">RM</label>
                                <input type="text" name="nilai"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $elaun->nilai }}" required>
                            </div>

 
                        </div>

                        <div class="flex gap-2 mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
                                        hover:bg-blue-700 focus:outline-none
                                        focus:ring-2 focus:ring-blue-500">
                                Submit
                            </button>
                            <a href="{{ route('gaji.index') }}"
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