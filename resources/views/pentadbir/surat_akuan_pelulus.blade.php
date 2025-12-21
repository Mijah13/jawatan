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
                    <h3 class="text-lg font-semibold mb-6">Pelulus Surat Akuan Perubatan</h3>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Add Form -->
                    <form method="POST" action="{{ route('pentadbir.surat_akuan_pelulus') }}" class="mb-8">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="idkakitangan" class="block text-sm font-medium text-gray-700 mb-2">Pegawai
                                    CIAST</label>
                                <select name="idkakitangan" id="idkakitangan" required
                                    class="w-full md:w-2/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Kakitangan</option>
                                    @foreach($kakitangan_list as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                                @error('idkakitangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tarikh" class="block text-sm font-medium text-gray-700 mb-2">Tarikh
                                    Mula</label>
                                <input type="date" name="tarikh" id="tarikh" value="{{ date('Y-m-d') }}" required
                                    class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('tarikh')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-md
                                hover:bg-blue-700 focus:outline-none
                                focus:ring-2 focus:ring-blue-500">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>

                    <h4 class="text-md font-semibold mb-4">Senarai Pegawai Pelulus</h4>

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
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Tarikh Mula
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Tindakan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rows as $index => $row)
                                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->nama }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ \Carbon\Carbon::parse($row->tarikh)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <a href="{{ route('pentadbir.surat_akuan_pelulus.edit', $row->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('pentadbir.surat_akuan_pelulus.destroy', $row->id) }}"
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