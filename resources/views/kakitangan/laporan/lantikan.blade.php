<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Maklumat Lantikan Penyandang
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

                    <p class="mb-4">
                        Laporan dikemaskini sehingga:
                        <strong>{{ $tarikh ? \Carbon\Carbon::parse($tarikh)->format('Y-m-d') : '-' }}</strong>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border">Bil</th>
                                    <th class="p-2 border">Kod</th>
                                    <th class="p-2 border">Jawatan</th>
                                    <th class="p-2 border">Gred</th>
                                    <th class="p-2 border">Nama</th>

                                    @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                        <th class="p-2 border">MyKad</th>
                                    @endif

                                    <th class="p-2 border">Ke CIAST</th>
                                    <th class="p-2 border">Lantikan Pertama</th>
                                    <th class="p-2 border">Lantikan Sekarang</th>
                                    <th class="p-2 border">Pengesahan</th>
                                    <th class="p-2 border">Memangku</th>
                                    <th class="p-2 border">Naik Pangkat</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($rows as $index => $row)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-yellow-50' : '' }}">
                                        <td class="p-2 border text-center">{{ $index + 1 }}</td>
                                        <td class="p-2 border text-center">{{ $row->kodpenempatan }}</td>
                                        <td class="p-2 border text-center">{{ $row->kodjawatan }}</td>
                                        <td class="p-2 border text-center">{{ $row->gred }}</td>
                                        <td class="p-2 border">{{ $row->nama }}</td>

                                        @if(auth()->user()->level == 1 || auth()->user()->level == 2)
                                            <td class="p-2 border text-center">{{ $row->mykad }}</td>
                                        @endif

                                        <td class="p-2 border text-center">{{ $row->tarikhkeciast }}</td>
                                        <td class="p-2 border text-center">{{ $row->tarikhlantikanpertama }}</td>
                                        <td class="p-2 border text-center">{{ $row->tarikhlantikansekarang }}</td>
                                        <td class="p-2 border text-center">{{ $row->tarikhpengesahanjawatan }}</td>
                                        <td class="p-2 border text-center">{{ $row->tarikhmemangku }}</td>
                                        <td class="p-2 border text-center">{{ $row->tarikhnaikpangkat }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="p-4 text-center text-gray-500">
                                            Tiada rekod dijumpai
                                        </td>
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