<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Latihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Laporan Latihan</h3>

                    <!-- Year Filter Form -->
                    <form method="POST" action="{{ route('latihan.laporan') }}" class="mb-6">
                        @csrf
                        <div class="flex items-center gap-4">
                            <label for="tahun" class="text-sm font-medium text-gray-700">Pilih Tahun:</label>
                            <select name="tahun" id="tahun"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($tahun_list as $tahun)
                                    <option value="{{ $tahun }}" {{ $tahun == $selected_tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
                                hover:bg-blue-700 focus:outline-none
                                focus:ring-2 focus:ring-blue-500">
                                Cari
                            </button>
                        </div>
                    </form>
                    <h3 class="text-lg font-semibold mb-6">Ringkasan Jumlah Hari Berkursus</h3>


                    <!-- Training Records Table -->
                    @if(count($rows) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                            Bil
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">
                                            Nama
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Program
                                        </th> 
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                            Jumlah Hari Berkursus
                                        </th> 
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rows as $index => $row)
                                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $row->nama }}
                                            </td>  
                                            <td class="px-6 py-4 text-sm text-gray-500 w-48 max-w-[400px]">
                                                <div class="truncate" title="{{ $row->program ?? 'Tidak berkaitan' }}">
                                                    @if ($row->program == null || $row->program == '')
                                                        -
                                                    @else
                                                        {{ $row->program }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 text-center">
                                                {{ $row->jumlah_hari }}
                                            </td> 
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            Tiada rekod latihan untuk tahun {{ $selected_tahun }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>