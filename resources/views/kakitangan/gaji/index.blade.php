<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Gaji') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="mb-4 text-lg font-bold">Gaji Pokok</h3>
                    <table class="w-1/2 mb-6 border border-collapse border-gray-300">
                        <tr>
                            <td class="w-1/3 p-2 border border-gray-300">Gaji Pokok</td>
                            <td class="p-2 font-bold border border-gray-300">
                                @if($gaji)
                                    {{ $gaji->gaji_pokok }}
                                @else
                                    Belum ada Data
                                @endif
                            </td>
                            <td class="p-2 border border-gray-300">
                                @if(!$gaji)
                                    <a href="{{ route('surat.index') }}" class="text-blue-600 hover:underline">Tambah</a>
                                @else
                                    <a href="#" class="text-blue-600 hover:underline">Edit</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 border border-gray-300">Nombor Gaji</td>
                            <td class="p-2 font-bold border border-gray-300">{{ $gaji->no_gaji ?? '' }}</td>
                            <td class="p-2 border border-gray-300"></td>
                        </tr>
                        <tr>
                            <td class="p-2 border border-gray-300">Gred Gaji</td>
                            <td class="p-2 font-bold border border-gray-300">{{ $gaji->gred_gaji ?? '' }}</td>
                            <td class="p-2 border border-gray-300"></td>
                        </tr>
                    </table>

                    <h3 class="mb-4 text-lg font-bold">Elaun</h3>
                    @if($elaun->count() > 0)
                        <table class="w-full text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Elaun Tetap</th>
                                    <th class="p-2 border border-gray-300">RM</th>
                                    <th class="p-2 border border-gray-300">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($elaun as $index => $e)
                                    <tr>
                                        <td class="p-2 border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $e->elaunRelation->nama ?? '' }}</td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $e->nilai }}
                                            @php $total += $e->nilai; @endphp
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="#" class="text-blue-600 hover:underline">Edit</a> |
                                            <a href="#" class="text-red-600 hover:underline">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-100">
                                    <td class="p-2 border border-gray-300"></td>
                                    <td class="p-2 text-right border border-gray-300">Jumlah Elaun</td>
                                    <td class="p-2 border border-gray-300">{{ $total }}</td>
                                    <td class="p-2 border border-gray-300"></td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p class="mb-4">Belum ada data</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('elaun.create') }}" class="text-blue-600 hover:underline">Tambah Elaun</a>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('surat.index') }}"
                            class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                            Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>