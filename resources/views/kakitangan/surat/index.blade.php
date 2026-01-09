<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Surat Pengesahan & Butir-Butir Perkhidmatan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mt-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 text-green-700 border border-green-200">
                    <strong>Berjaya!</strong> {{ session('success') }}
                </div>
            @endif

            <!-- Action Cards -->
            <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('gaji.index') }}" class="p-4 bg-white border rounded-lg hover:shadow transition">
                    <p class="font-semibold text-gray-800">Kemaskini Gaji</p>
                    <p class="text-sm text-gray-500">Maklumat gaji terkini</p>
                </a>

                <a href="{{ route('taraf.edit') }}" class="p-4 bg-white border rounded-lg hover:shadow transition">
                    <p class="font-semibold text-gray-800">Taraf Perkhidmatan</p>
                    <p class="text-sm text-gray-500">Status perkhidmatan</p>
                </a>

                <a href="https://epenyatagaji-laporan.anm.gov.my/Layouts/Login/Login.aspx" target="_blank"
                    class="p-4 bg-white border rounded-lg hover:shadow transition">
                    <p class="font-semibold text-gray-800">Penyata Gaji</p>
                    <p class="text-sm text-gray-500">Download penyata</p>
                </a>

                <a href="{{ route('surat.create') }}" class="p-4 bg-white border rounded-lg hover:shadow transition">
                    <p class="font-semibold text-gray-800">Permohonan Baru</p>
                    <p class="text-sm text-gray-500">Mohon surat pengesahan</p>
                </a>
            </div>

            <!-- Content Card -->
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="mb-4 text-lg font-semibold text-gray-800">
                    Senarai Permohonan & Status
                </h3>

                @if($surat->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-collapse border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 border">Bil</th>
                                    <th class="p-3 border">Kepada</th>
                                    <th class="p-3 border">Tarikh Mohon</th>
                                    <th class="p-3 border">Status</th>
                                    <th class="p-3 border">Tarikh Sah</th>
                                    <th class="p-3 border">No Rujukan</th>
                                    <th class="p-3 border text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surat as $index => $s)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border">{{ $index + 1 }}</td>
                                        <td class="p-3 border">{{ $s->kepada }}</td>
                                        <td class="p-3 border">
                                            {{ optional($s->tarikhmohon)->format('d/m/Y') }}
                                        </td>
                                        <td class="p-3 border">
                                            @if($s->status == 1)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">
                                                    Telah Selesai
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded">
                                                    Belum Selesai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3 border">
                                            {{ optional($s->tarikh_sah)->format('d/m/Y') }}
                                        </td>
                                        <td class="p-3 border">
                                            {{ $s->fail }} ({{ $s->id }})
                                        </td>
                                        <td class="p-3 border text-center space-x-2">
                                            <a href="{{ route('surat.cetak', $s->id) }}" class="text-indigo-600 hover:underline text-sm">
                                                Cetak
                                            </a>
                                            @if($s->status != 1)
                                                <a href="{{ route('surat.edit', $s->id) }}" class="text-gray-600 hover:underline text-sm">
                                                    Edit
                                                </a>
                                            @endif
                                        </td> 
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="py-12 text-center text-gray-500">
                        <p class="text-lg font-medium">Tiada Permohonan</p>
                        <p class="text-sm mb-4">Anda belum membuat sebarang permohonan surat.</p>
                        <a href="{{ route('surat.create') }}"
                            class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Buat Permohonan Baru
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>