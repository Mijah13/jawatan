<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Surat Akuan Perubatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            <span class="font-medium">Berjaya!</span> {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('surat_akuan.create') }}"
                            class="inline-block px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700">
                            Mohon Surat Akuan
                        </a>
                    </div>

                    <h3 class="mb-4 text-lg font-bold">Senarai Permohonan</h3>
                    @if($surat->count() > 0)
                        <table class="w-full text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Nama Pesakit</th>
                                    <th class="p-2 border border-gray-300">Klinik / Hospital</th>
                                    <th class="p-2 border border-gray-300">Kelayakan Wad</th>
                                    <th class="p-2 border border-gray-300">No Rujukan</th>
                                    <th class="p-2 border border-gray-300">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surat as $index => $s)
                                    <tr>
                                        <td class="p-2 border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $s->nama_pesakit }}</td>
                                        <td class="p-2 border border-gray-300">{{ $s->hospital }}</td>
                                        <td class="p-2 border border-gray-300">{{ $s->kelayakan }}</td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $s->no_rujukan ? 'CIAST 500-2/19/1(' . $s->no_rujukan . ')' : '-' }}
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="#" class="text-blue-600 hover:underline">Cetak</a>
                                            @if(!$s->no_rujukan)
                                                | <a href="#" class="text-blue-600 hover:underline">Edit</a>
                                                | <a href="#" class="text-red-600 hover:underline">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Tiada permohonan</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>