<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan Kakitangan Bersara') }}
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

                    <h3 class="mb-4 text-lg font-bold">Pegawai Bersara</h3>
                    <p class="mb-4">Laporan dikemaskini sehingga:
                        <strong>{{ $tarikh ? \Carbon\Carbon::parse($tarikh)->format('Y-m-d') : '-' }}</strong></p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Nama</th>
                                    @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                        <th class="p-2 border border-gray-300">MyKad</th>
                                    @endif
                                    <th class="p-2 border border-gray-300">Lantikan Pertama</th>
                                    <th class="p-2 border border-gray-300">Tarikh Ke CIAST</th>
                                    <th class="p-2 border border-gray-300">Tarikh Bersara</th>
                                    <th class="p-2 border border-gray-300">Tempat bertukar / Alamat tempat Bersara</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $row->nama }}</td>
                                        @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                            <td class="p-2 text-center border border-gray-300">{{ $row->mykad }}</td>
                                        @endif
                                        <td class="p-2 text-center border border-gray-300">
                                            {{ $row->tarikhlantikanpertama ? \Carbon\Carbon::parse($row->tarikhlantikanpertama)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="p-2 text-center border border-gray-300">
                                            {{ $row->tarikhkeciast ? \Carbon\Carbon::parse($row->tarikhkeciast)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="p-2 text-center border border-gray-300">
                                            {{ $row->tarikhbertukarkeluar ? \Carbon\Carbon::parse($row->tarikhbertukarkeluar)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="p-2 border border-gray-300">{{ $row->penempatanbaru }}</td>
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