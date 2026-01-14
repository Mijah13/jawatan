<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah - Pingat Kebesaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            <span class="font-medium">Berjaya!</span> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>
                            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pingat.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_kakitangan" value="{{ $kakitangan->id }}">

                        <table class="w-full table-auto">
                            <tr>
                                <td class="w-1/4 p-2 bg-yellow-100">Nama</td>
                                <td class="p-2 bg-yellow-100 font-bold">{{ $kakitangan->nama }}</td>
                            </tr>
                            <tr>
                                <td class="p-2">MyKAD</td>
                                <td class="p-2 font-bold">{{ $kakitangan->mykad }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 bg-yellow-100">Pingat Kebesaran</td>
                                <td class="p-2 bg-yellow-100">
                                    <input type="text" name="pingat" class="w-full p-2 border rounded" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2">Tarikh Terima</td>
                                <td class="p-2">
                                    <input type="date" name="tarikhterima" class="p-2 border rounded" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 bg-yellow-100"></td>
                                <td class="p-2 bg-yellow-100">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
           hover:bg-blue-700 focus:outline-none
           focus:ring-2 focus:ring-blue-500">
                                        Submit
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </form>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold">Senarai Pingat</h3>
                        <table class="w-full mt-4 border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="p-2 border border-gray-300">Bil</th>
                                    <th class="p-2 border border-gray-300">Pingat</th>
                                    <th class="p-2 border border-gray-300">Tarikh Terima</th>
                                    <th class="p-2 border border-gray-300">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pingat_list as $index => $pingat)
                                    <tr>
                                        <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="p-2 border border-gray-300">{{ $pingat->pingat }}</td>
                                        <td class="p-2 text-center border border-gray-300">
                                            {{ $pingat->tarikhterima->format('d/m/Y') }}
                                        </td>
                                        <td class="p-3 border text-center space-x-2">
                                            <a href="{{ route('pingat.edit', $pingat->id) }}"
                                                class="text-indigo-600 hover:underline">Edit</a>
                                            <form action="{{ route('pingat.destroy', $pingat->id) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Adakah anda pasti?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Padam</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center border border-gray-300">Tiada rekod.</td>
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