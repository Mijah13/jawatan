<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Latihan Kakitangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Berjaya!</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg" role="alert">
                    <span class="font-medium">Ralat!</span> {{ session('error') }}
                </div>
            @endif


            <!-- Jumlah Hari Berkursus -->
            <div class="mb-8 bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Jumlah Hari Berkursus</h3>

                @if($hari->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[400px] text-left border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 border">Tahun</th>
                                    <th class="p-3 border">Bilangan Hari Berkursus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hari as $h)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border">{{ $h->tahun }}</td>
                                        <td class="p-3 border">{{ $h->jumlahhari }} hari</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Tiada rekod hari berkursus.</p>
                @endif
            </div>

            <!-- Senarai Latihan Kakitangan -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Senarai Latihan Kakitangan</h3>
                    <a href="{{ route('latihan.create') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-black bg-gray-700 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Tambah Latihan
                    </a>

                </div>

                @if($latihan->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px] text-sm border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 border">Bil</th>
                                    <th class="p-3 border">Tajuk</th>
                                    <th class="p-3 border">Kategori</th>
                                    <th class="p-3 border">Jenis</th>
                                    <th class="p-3 border">Mula</th>
                                    <th class="p-3 border">Tamat</th>
                                    <th class="p-3 border">Tempoh</th>
                                    <th class="p-3 border">Tempat</th>
                                    <th class="p-3 border">Penganjur</th>
                                    <th class="p-3 border text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latihan as $index => $l)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border">{{ $index + 1 }}</td>
                                        <td class="p-3 border font-semibold">{{ strtoupper($l->tajuk) }}</td>
                                        <td class="p-3 border">{{ $l->nama_kategori }}</td>
                                        <td class="p-3 border">{{ $l->nama_jenis }}</td>
                                        <td class="p-3 border">{{ $l->mula?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="p-3 border">{{ $l->tamat?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="p-3 border">{{ $l->tempoh }} hari</td>
                                        <td class="p-3 border">{{ $l->tempat }}</td>
                                        <td class="p-3 border">{{ $l->penganjur }}</td>
                                        <td class="p-3 border text-center space-x-2">
                                            <a href="#" class="text-indigo-600 hover:underline">Edit</a>
                                            <form action="{{ route('latihan.destroy', $l->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Adakah anda pasti?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Padam</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Tiada latihan</p>
                @endif
            </div>

            <!-- Back Button -->
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