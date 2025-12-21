<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Kemaskini Maklumat - Program</h3>

                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('pentadbir.program.update', $item->id) }}" class="mb-8">
                        @csrf
                        @method('PUT')
                        <div class="bg-yellow-50 p-4 rounded-md">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="kod" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kod
                                    </label>
                                    <input type="text" name="kod" id="kod" maxlength="50"
                                        value="{{ old('kod', $item->kod) }}" required
                                        class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('kod')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                        Program / Bahagian
                                    </label>
                                    <input type="text" name="nama" id="nama" maxlength="200"
                                        value="{{ old('nama', $item->program) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('nama')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-sm">
                                        Kemaskini
                                    </button>
                                    <a href="{{ route('pentadbir.program') }}"
                                        class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 font-semibold text-sm">
                                        Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>