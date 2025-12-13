<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Surat Pengesahan dan Butir-Butir Perkhidmatan') }}
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

                    <ol class="mb-6 ml-6 list-decimal">
                        <li><a href="{{ route('gaji.index') }}" class="text-blue-600 hover:underline">Kemaskini maklumat
                                gaji</a></li>
                        <li><a href="{{ route('taraf.edit') }}" class="text-blue-600 hover:underline">Kemaskini taraf
                                perkhidmatan</a></li>
                        <li><a href="https://epenyatagaji-laporan.anm.gov.my/Layouts/Login/Login.aspx" target="_blank"
                                class="text-blue-600 hover:underline">Download Penyata Gaji</a></li>
                        <li><a href="{{ route('surat.create') }}" class="text-blue-600 hover:underline">Permohonan
                                baru</a></li>
                    </ol>

                    <h3 class="mb-4 text-lg font-bold">Senarai Permohonan dan Status</h3>

                    @if($surat->count() > 0)
                        <table class="w-full text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Kepada</th>
                                    <th class="p-2 border border-gray-300">Tarikh Mohon</th>
                                    <th class="p-2 border border-gray-300">Status</th>
                                    <th class="p-2 border border-gray-300">Tarikh Sah</th>
                                    <th class="p-2 border border-gray-300">No Rujukan</th>
                                    <th class="p-2 border border-gray-300">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surat as $index => $s)
                                    <tr>
                                        <td class="p-2 border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $s->kepada }}</td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $s->tarikhmohon ? $s->tarikhmohon->format('Y-m-d') : '' }}
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $s->status == 1 ? 'Telah Selesai' : 'Belum Selesai' }}
                                        </td>
                                        <td class="p-2 border border-gray-300">
                                            {{ $s->tarikh_sah ? $s->tarikh_sah->format('Y-m-d') : '' }}
                                        </td>
                                        <td class="p-2 border border-gray-300">{{ $s->fail }} ({{ $s->id }})</td>
                                        <td class="p-2 border border-gray-300">
                                            <a href="#" class="text-blue-600 hover:underline">Cetak</a>
                                            @if($s->status != 1)
                                                | <a href="#" class="text-blue-600 hover:underline">Edit</a>
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