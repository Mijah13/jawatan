<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Gaji
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Gaji Pokok -->
            <div class="mb-8 bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Gaji Pokok</h3>

                    @if(!$gaji)
                        <a href="{{ route('gaji.create') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-black
                                                                  bg-indigo-600 rounded-md shadow
                                                                  hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Tambah
                        </a>
                    @else
                        <a href="{{ route('gaji.gaji_edit', $gaji->idkakitangan) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold
                                                                  text-indigo-600 border border-indigo-600 rounded-md
                                                                  hover:bg-indigo-50">
                            Edit
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-sm text-gray-500">Gaji Pokok</p>
                        <p class="font-semibold">{{ $gaji->gaji_pokok ?? 'Belum ada data' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nombor Gaji</p>
                        <p class="font-semibold">{{ $gaji->no_gaji ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Gred Gaji</p>
                        <p class="font-semibold">{{ $gaji->gred_gaji ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Elaun -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Elaun</h3>

                    <a href="{{ route('elaun.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-black
                              bg-indigo-600 rounded-md shadow
                              hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Tambah Elaun
                    </a>
                </div>

                @if($elaun->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">

                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="p-3 text-center border">Bil</th>
                                    <th class="p-3 text-left border">Elaun Tetap</th>
                                    <th class="p-3 text-right border">Jumlah (RM)</th>
                                    <th class="p-3 text-center border">Tindakan</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white">
                                @php $total = 0; @endphp

                                @foreach($elaun as $index => $e)
                                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50">
                                        <td class="p-3 text-center border">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="p-3 border">
                                            {{ $e->elaunRelation->nama ?? '-' }}
                                        </td>

                                        <td class="p-3 text-right border font-medium">
                                            {{ number_format($e->nilai, 2) }}
                                            @php $total += $e->nilai; @endphp
                                        </td>

                                        <td class="p-3 text-center border space-x-2">
                                            <a href="{{ route('elaun.edit_elaun', $e->id) }}" class="inline-block px-3 py-1 text-xs font-semibold text-indigo-600
                                                    border border-indigo-600 rounded hover:bg-indigo-50">
                                                Edit
                                            </a>

                                            <a href="{{ route('elaun.destroy_elaun', $e->id) }}" onclick="return confirm('Adakah anda ingin menghapus data ini?');" class="inline-block px-3 py-1 text-xs font-semibold text-red-600
                                                    border border-red-600 rounded hover:bg-red-50">
                                                Padam
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <!-- Total -->
                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="2" class="p-3 text-right border">
                                        Jumlah Elaun
                                    </td>
                                    <td class="p-3 text-right border text-indigo-700">
                                        {{ number_format($total, 2) }}
                                    </td>
                                    <td class="p-3 border"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                @else
                    <!-- Empty state -->
                    <div class="py-10 text-center text-gray-500">
                        <p class="mb-2 font-medium">Belum ada elaun direkodkan</p>
                        <a href="{{ route('elaun.create') }}"
                            class="inline-block px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                            Tambah Elaun
                        </a>
                    </div>
                @endif
            </div>

            <!-- Back -->
            <div class="mt-6">
                <a href="{{ route('surat.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold
              text-gray-700 bg-gray-200 rounded-md
              hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Kembali
                </a>
            </div>

        </div>
    </div>
</x-app-layout>