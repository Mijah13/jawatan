<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pentadbir Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Senarai Surat Akuan Perubatan</h3>

                    <!-- Data Table -->
                    @if(count($rows) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                            Bil
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Kakitangan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Pesakit
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hospital
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombor Rujukan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                            Tindakan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rows as $index => $row)
                                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->namakakitangan }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->pesakit }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->hospital }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @if($row->no_rujukan)
                                                    CIAST 500-2/19/1({{ $row->no_rujukan }})
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <a href="{{ route('pentadbir.surat_akuan_senarai.edit', $row->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('pentadbir.surat_akuan_senarai.destroy', $row->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Adakah anda pasti?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            Tiada rekod.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>