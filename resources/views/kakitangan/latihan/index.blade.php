<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Latihan Kakitangan') }}
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

                    @if(session('error'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                            role="alert">
                            <span class="font-medium">Ralat!</span> {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('latihan.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md
           hover:bg-blue-700 focus:outline-none
           focus:ring-2 focus:ring-blue-500">
                            Tambah Latihan
                        </a>
                    </div>

                    <h3 class="mb-4 text-lg font-bold">Jumlah Hari Berkursus</h3>
                    @if($hari->count() > 0)
                        <table class="w-full mb-8 text-left border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border border-gray-300">Tahun</th>
                                    <th class="p-2 border border-gray-300">Bilangan Hari Berkursus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hari as $h)
                                    <tr>
                                        <td class="p-2 border border-gray-300">{{ $h->tahun }}</td>
                                        <td class="p-2 border border-gray-300">{{ $h->jumlahhari }} hari</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="mb-8">Tiada rekod hari berkursus.</p>
                    @endif

                    <h3 class="mb-4 text-lg font-bold">Senarai Latihan Kakitangan</h3>
                    @if($latihan->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border border-collapse border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 border border-gray-300">Bil</th>
                                        <th class="p-2 border border-gray-300">Tajuk</th>
                                        <th class="p-2 border border-gray-300">Kategori</th>
                                        <th class="p-2 border border-gray-300">Jenis</th>
                                        <th class="p-2 border border-gray-300">Mula</th>
                                        <th class="p-2 border border-gray-300">Tamat</th>
                                        <th class="p-2 border border-gray-300">Tempoh</th>
                                        <th class="p-2 border border-gray-300">Tempat</th>
                                        <th class="p-2 border border-gray-300">Penganjur</th>
                                        <th class="p-2 border border-gray-300">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latihan as $index => $l)
                                        <tr>
                                            <td class="p-2 border border-gray-300">{{ $index + 1 }}</td>
                                            <td class="p-2 border border-gray-300">{{ strtoupper($l->tajuk) }}</td>
                                            <td class="p-2 border border-gray-300">{{ $l->nama_kategori }}</td>
                                            <td class="p-2 border border-gray-300">{{ $l->nama_jenis }}</td>
                                            <td class="p-2 border border-gray-300">
                                                {{ $l->mula ? $l->mula->format('Y-m-d') : '' }}
                                            </td>
                                            <td class="p-2 border border-gray-300">
                                                {{ $l->tamat ? $l->tamat->format('Y-m-d') : '' }}
                                            </td>
                                            <td class="p-2 border border-gray-300">{{ $l->tempoh }} hari</td>
                                            <td class="p-2 border border-gray-300">{{ $l->tempat }}</td>
                                            <td class="p-2 border border-gray-300">{{ $l->penganjur }}</td>
                                            <td class="p-2 border border-gray-300">
                                                <a href="#" class="text-blue-600 hover:underline">Edit</a> |
                                                <form action="{{ route('latihan.destroy', $l->id) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Adakah anda pasti?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Tiada latihan</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>