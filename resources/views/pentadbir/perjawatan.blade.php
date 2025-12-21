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
                    <h3 class="text-lg font-semibold mb-6">Tambah - Perjawatan</h3>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Add Form -->
                    <form method="POST" action="{{ route('pentadbir.perjawatan') }}" class="mb-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <label for="jawatan"
                                    class="block text-sm font-medium text-gray-700 mb-2">Jawatan</label>
                                <select name="jawatan" id="jawatan" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Jawatan --</option>
                                    @foreach($jawatan_list as $j)
                                        <option value="{{ $j->id }}">{{ $j->jawatan }}</option>
                                    @endforeach
                                </select>
                                @error('jawatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="gred" class="block text-sm font-medium text-gray-700 mb-2">Gred</label>
                                <select name="gred" id="gred" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Gred --</option>
                                    @foreach($gred_list as $g)
                                        <option value="{{ $g->id }}">{{ $g->gred }}</option>
                                    @endforeach
                                </select>
                                @error('gred')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <label for="program"
                                    class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                                <select name="program" id="program" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Program --</option>
                                    @foreach($program_list as $p)
                                        <option value="{{ $p->id }}">{{ $p->program }}</option>
                                    @endforeach
                                </select>
                                @error('program')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <select name="unit" id="unit" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach($unit_list as $u)
                                        <option value="{{ $u->id }}">{{ $u->unit }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 bg-yellow-50 p-4 rounded-md">
                            <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-md
                                hover:bg-blue-700 focus:outline-none
                                focus:ring-2 focus:ring-blue-500">
                                Submit
                            </button>
                        </div>
                    </form>

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
                                            Jawatan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gred
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Program
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Unit
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                            Tindakan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($rows as $index => $row)
                                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-yellow-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->jawatanRel->jawatan ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $row->gredRel->gred ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->organisasiRel->program ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $row->unitRel->unit ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <span class="text-gray-300">|</span>
                                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>