<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem - Penetapan Unit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @error('program')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror

                    <!-- Form Section -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Unit Baru</h3>
                        <form action="{{ route('pentadbir.unit') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="kod" :value="__('Kod Unit')" />
                                    <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                                        :value="old('unit')" required autofocus />
                                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="program" :value="__('Program')" />
                                    <select id="program" name="program"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">-- Sila Pilih Program --</option>
                                        @foreach($program_list as $prog)
                                            <option value="{{ $prog->id }}">{{ $prog->program }} ({{ $prog->kod }})</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('program')" class="mt-2" />
                                </div>

                                <button type="submit" class="mt-3 mb-3 px-4 py-2 bg-blue-600 text-white rounded-md
                                    hover:bg-blue-700 focus:outline-none
                                    focus:ring-2 focus:ring-blue-500">
                                    Submit
                                </button>
                        </form>


                        <div class="md:col-span-2">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">Bil</th>
                                        <th class="py-3 px-6 text-left">Kod Program</th>
                                        <th class="py-3 px-6 text-left">Unit</th>
                                        <th class="py-3 px-6 text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @forelse($rows as $index => $row)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $index + 1 }}</td>
                                            <td class="py-3 px-6 text-left">{{ $row->kod }}</td>
                                            <td class="py-3 px-6 text-left">{{ $row->unit }}</td>
                                            <td class="py-3 px-6 text-center">
                                                <a href="{{ route('pentadbir.unit.edit', $row->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit </a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('pentadbir.unit.destroy', $row->id) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Adakah anda pasti?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-3 px-6 text-center">Tiada rekod dijumpai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
</x-app-layout>