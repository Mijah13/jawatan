<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Senarai Nama Penerima APC dan Pingat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-md
           hover:bg-blue-700 focus:outline-none
           focus:ring-2 focus:ring-blue-500">
                            Cetak
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Nama</th>
                                    <th class="p-2 border border-gray-300">Jawatan</th>
                                    <th class="p-2 border border-gray-300">Gred</th>
                                    <th class="p-2 border border-gray-300">APC</th>
                                    <th class="p-2 border border-gray-300">Pingat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->nama }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->jawatan }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->gred }}</td>
                                        <td class="p-2 border border-gray-300">
                                            @foreach($row->apc as $apc)
                                                <div>{{ $apc->tahunterima }}</div>
                                            @endforeach
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            @foreach($row->pingat as $pingat)
                                                <div>{{ $pingat->pingat }}</div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>