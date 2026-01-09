<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mohon Surat Pengesahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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

                    <form method="POST" action="{{ route('surat.update', $surat->id) }}"
                        onsubmit="return confirm('Adakah anda ingin kemaskini data ini?');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="fail" value="{{ $fail->running ?? '' }}">

                        <div class="grid grid-cols-1 gap-6">

                            <!-- Nama Penerima -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nama penerima</label>
                                <input type="text" name="penerima"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $surat->kepada }}">
                            </div>

                            <!-- Alamat 1 -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 font-bold text-gray-700">Alamat</label>
                                    <input type="text" name="alamat1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                        value="{{ $surat->alamat1 }}">
                                </div>

                                <div>
                                    <label class="block mb-2 font-bold text-gray-700 invisible">&nbsp;</label>
                                    <input type="text" name="alamat2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                        value="{{ $surat->alamat2 }}">
                                </div>
                            </div>


                            <!-- Poskod -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Poskod</label>
                                <input type="number" name="poskod"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $surat->poskod }}" >
                            </div>

                            <!-- Bandar -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Bandar</label>
                                <input type="text" name="bandar"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ $surat->bandar }}" >
                            </div>

                            <!-- Negeri -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Negeri</label>
                                <select name="negeri"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    >
                                    <option value="">Pilih Negeri</option>
                                    @foreach($negeri as $n)
                                        <option value="{{ $n->id }}" {{ $surat->negeri == $n->id ? 'selected' : '' }}>
                                            {{ $n->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="flex gap-2 mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md
                                hover:bg-blue-700 focus:outline-none
                                focus:ring-2 focus:ring-blue-500">
                                Submit
                            </button>
                            <a href="{{ route('surat.index') }}"
                                class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                                Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>