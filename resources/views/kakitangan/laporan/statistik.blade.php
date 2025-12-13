<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan Statistik Kakitangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <button onclick="window.print()"
                            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Cetak
                        </button>
                    </div>

                    <h3 class="mb-4 text-lg font-bold">LAPORAN MENGIKUT KOD PENEMPATAN</h3>
                    <hr class="mb-4" />

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Kod Penempatan</th>
                                    <th class="p-2 border border-gray-300">Keterangan</th>
                                    <th class="p-2 border border-gray-300">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($rows as $index => $row)
                                    @php $total += $row->jumlah; @endphp
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->kod }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->jenis }}</td>
                                        <td class="p-2 text-center border border-gray-300">{{ $row->jumlah }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-200">
                                    <td colspan="3" class="p-2 text-right border border-gray-300">JUMLAH KESELURUHAN
                                    </td>
                                    <td class="p-2 text-center border border-gray-300">{{ $total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>