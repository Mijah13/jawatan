<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tambah - Pencapaian') }}
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

                    <form action="{{ route('pencapaian.update', ['id' => $pencapaian->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
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
                                <td class="p-2 bg-yellow-100">Pencapaian</td>
                                <td class="p-2 bg-yellow-100">
                                    <textarea name="pencapaian" class="w-full p-2 border rounded" rows="4"
                                        required>{{ $pencapaian->pencapaian }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2">Peringkat</td>
                                <td class="p-2">
                                    <select name="peringkat" class="w-full p-2 border rounded">
                                        <option value="">Peringkat</option>
                                        @foreach($peringkat_list as $peringkat)
                                            <option value="{{ $peringkat->id }}" {{ $pencapaian->peringkat == $peringkat->id ? 'selected' : '' }}>{{ $peringkat->peringkat }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 bg-yellow-100">Tarikh Pencapaian</td>
                                <td class="p-2 bg-yellow-100">
                                    <input type="date" name="tarikhpencapaian" class="p-2 border rounded" required
                                        value="{{ $pencapaian->tarikhpencapaian ? $pencapaian->tarikhpencapaian->format('Y-m-d') : '' }}">
                                </td>
                            </tr>
                           <tr>
                                <td class="p-2"></td>
                                <td class="p-2 flex gap-2 mt-6">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
                                        hover:bg-blue-700 focus:outline-none
                                        focus:ring-2 focus:ring-blue-500">
                                        Submit
                                    </button>
                                    <a href="{{ route('pencapaian.create', ['id' => $pencapaian->id_kakitangan]) }}"
                                        class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                                        Back
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </form> 

                </div>
            </div>
        </div>
    </div>
</x-app-layout>