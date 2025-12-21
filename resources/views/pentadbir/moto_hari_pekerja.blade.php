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
                    <h3 class="text-lg font-semibold mb-6">Senarai / Tambah / Edit Moto Hari Pekerja</h3>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Add Form -->
                    <form method="POST" action="{{ route('pentadbir.moto_hari_pekerja') }}" class="mb-8">
                        @csrf
                        <div class="space-y-4">
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                                <input type="number" name="tahun" id="tahun" required
                                    class="w-full md:w-1/4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('tahun')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="moto" class="block text-sm font-medium text-gray-700 mb-2">Moto</label>
                                <input type="text" name="moto" id="moto" maxlength="200" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('moto')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-md
                                    hover:bg-blue-700 focus:outline-none
                                    focus:ring-2 focus:ring-blue-500">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Data Table -->
                    @if(count($rows) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                            Bil
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Tahun
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Moto
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Tindakan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rows as $index => $row)
                                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-yellow-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $row->tahun }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->moto }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <a href="{{ route('pentadbir.moto_hari_pekerja.edit', $row->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit </a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('pentadbir.moto_hari_pekerja.destroy', $row->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Adakah anda pasti?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>