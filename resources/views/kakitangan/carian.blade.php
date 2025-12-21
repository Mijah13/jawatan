<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Carian Kakitangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('kakitangan.carian') }}" class="mb-6">
                        <div class="flex gap-2">
                            <input type="text" name="cari" placeholder="Carian Nama atau MyKAD"
                                class="w-full p-2 border rounded" value="{{ request('cari') }}">

                            <button class="px-4 py-2 text-white bg-blue-600 rounded">Cari</button>
                        </div>
                    </form>

                    @if(isset($rows) && $rows->count() > 0)
                        <table class="w-full border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 border">Nama</th>
                                    <th class="p-2 border">MyKAD</th>
                                    <th class="p-2 border">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td class="p-2 border">{{ $row->nama }}</td>
                                        <td class="p-2 border">{{ $row->mykad }}</td>
                                        <td class="p-2 border border-gray-300">

                                            {{-- Perincian (always show) --}}
                                            <a href="{{ route('kakitangan.display', $row->id) }}"
                                                class="text-blue-600 hover:underline">
                                                Perincian
                                            </a>

                                            {{-- For level 1 and 2 only --}}
                                            @if(auth()->user()->level == 1 || auth()->user()->level == 2)


                                                | <a href="{{ route('kakitangan.edit', $row->id) }}" target="_blank"
                                                    class="text-green-600 hover:underline">
                                                    Edit
                                                </a>

                                                | <a href="{{ route('harta.create', $row->id) }}" target="_blank"
                                                    class="text-yellow-600 hover:underline">
                                                    Harta
                                                </a>

                                                | <a href="{{ route('apc.create', $row->id) }}" target="_blank"
                                                    class="text-purple-600 hover:underline">
                                                    APC
                                                </a>

                                                | <a href="{{ route('pingat.create', $row->id) }}" target="_blank"
                                                    class="text-pink-600 hover:underline">
                                                    Pingat
                                                </a>

                                                | <a href="{{ route('pencapaian.create', $row->id) }}" target="_blank"
                                                    class="text-orange-600 hover:underline">
                                                    Pencapaian
                                                </a>

                                                |
                                                <a href="{{ route('kakitangan.reset', $row->id) }}" target="_blank"
                                                    onclick="return confirm('Are you confirm to reset?')"
                                                    class="text-indigo-600 hover:underline">
                                                    Reset Password
                                                </a>
                                                |
                                                <a href="{{ route('kakitangan.delete', $row->id) }}"
                                                    onclick="return confirm('Padam rekod ini?')"
                                                    class="text-red-600 hover:underline">
                                                    Delete
                                                </a>

                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif(isset($rows))
                        <p class="text-red-600">Tiada rekod ditemui.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>