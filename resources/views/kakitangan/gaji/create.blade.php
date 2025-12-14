<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah Maklumat Gaji') }}
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

                    <form method="POST" action="{{ route('gaji.store') }}"
                        onsubmit="return confirm('Adakah anda ingin menambah data ini?');">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">

                            <!-- Nombor Gaji -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nombor Gaji</label>
                                <input type="text" name="no_gaji"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('no_gaji') }}" required>
                            </div>

                            <!-- Gaji Pokok -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Gaji Pokok</label>
                                <input type="number" step="0.01" name="gaji"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('gaji') }}" required>
                            </div>

                            <!-- Gred Gaji -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Gred Gaji</label>
                                <input type="text" name="gred_gaji"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('gred_gaji') }}" required>
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