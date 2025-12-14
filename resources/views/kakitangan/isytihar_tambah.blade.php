<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah - Isytihar Harta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
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

                    <form action="{{ route('harta.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_kakitangan" value="{{ $kakitangan->id }}">

                        <table class="w-full table-auto">
                            <tr>
                                <td class="w-1/4 p-2 bg-yellow-100">Nama</td>
                                <td class="p-2 bg-yellow-100 font-bold">{{ $kakitangan->nama }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">MyKAD</td>
                                <td class="p-2 font-bold">{{ $kakitangan->mykad }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 bg-yellow-100">Jenis Isytihar</td>
                                <td class="p-2 bg-yellow-100">
                                    <select name="jenis" class="w-full p-2 border rounded">
                                        @foreach($jenis_list as $jenis)
                                            <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2">Tarikh Isytihar</td>
                                <td class="p-2">
                                    <input type="date" name="tarikhisytihar" class="p-2 border rounded" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 bg-yellow-100">Nombor Rujukan</td>
                                <td class="p-2 bg-yellow-100">
                                    <input type="text" name="no_rujukan" class="w-full p-2 border rounded" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2"></td>
                                <td class="p-2">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
           hover:bg-blue-700 focus:outline-none
           focus:ring-2 focus:ring-blue-500">
                                        Submit
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </form>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold">Senarai Harta Diisytihar</h3>
                        <table class="w-full mt-4 border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Jenis</th>
                                    <th class="p-2 border border-gray-300">Tarikh Isytihar</th>
                                    <th class="p-2 border border-gray-300">Nombor Rujukan</th>
                                    <th class="p-2 border border-gray-300">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($harta_list as $index => $harta)
                                    <tr>
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $harta->jenisIsytihar->jenis ?? '-' }}
                                        </td>
                                        <td class="p-2 text-center border border-gray-300">
                                            {{ $harta->tarikhisytihar->format('d/m/Y') }}
                                        </td>
                                        <td class="p-2 border border-gray-300">{{ $harta->no_rujukan }}</td>
                                        <td class="p-2 text-center border border-gray-300">
                                            <!-- Actions (Edit/Delete) can be added here later -->
                                            -
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center border border-gray-300">Tiada rekod.</td>
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