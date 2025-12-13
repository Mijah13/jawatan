<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            <span class="font-medium">Berjaya!</span> {{ session('success') }}
                        </div>
                    @endif

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

                    <form method="POST" action="{{ route('keluarga.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                            <!-- Nama -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nama</label>
                                <input type="text" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="{{ old('nama') }}" required>
                            </div>

                            <!-- Hubungan -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Hubungan</label>
                                <select name="hubungan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                                    <option value="">Pilih Hubungan</option>
                                    @foreach(\App\Models\Hubungan::orderBy('hubungan')->get() as $h)
                                        <option value="{{ $h->id }}" {{ old('hubungan') == $h->id ? 'selected' : '' }}>{{ $h->hubungan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                Tambah
                            </button>
                        </div>
                    </form>

                    <h3 class="mb-4 text-lg font-bold">Senarai Keluarga</h3>
                    <table class="w-full text-left border border-collapse border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border border-gray-300">Bil</th>
                                <th class="p-2 border border-gray-300">Nama</th>
                                <th class="p-2 border border-gray-300">Hubungan</th>
                                <th class="p-2 border border-gray-300">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $index => $row)
                                <tr>
                                    <td class="p-2 border border-gray-300">{{ $index + 1 }}</td>
                                    <td class="p-2 border border-gray-300">{{ $row->nama }}</td>
                                    <td class="p-2 border border-gray-300">{{ $row->hubunganInfo->hubungan ?? '' }}</td>
                                    <td class="p-2 border border-gray-300">
                                        <a href="{{ route('keluarga.edit', $row->id) }}" class="text-blue-600 hover:underline">Edit</a> | 
                                        <form action="{{ route('keluarga.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Adakah anda pasti?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
