<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mohon Surat Akuan Perubatan') }}
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

                    <form method="POST" action="{{ route('surat_akuan.store') }}"
                        onsubmit="return confirm('Adakah anda ingin memohon?');">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <!-- Nama Hospital -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nama Hospital / Klinik</label>
                                <input type="text" name="hospital"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="{{ old('hospital') }}" required>
                            </div>

                            <!-- Nama Pesakit -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Nama Pesakit</label>
                                <select name="pesakit"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    required>
                                    <option value="">Pilih Pesakit</option>
                                    @foreach($pesakit as $p)
                                        <option value="{{ $p->id }}" {{ old('pesakit') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Wad -->
                            <div>
                                <label class="block mb-2 font-bold text-gray-700">Wad</label>
                                <select name="wad"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    required>
                                    <option value="">Pilih Wad</option>
                                    @foreach($wad as $w)
                                        <option value="{{ $w->id }}" {{ old('wad') == $w->id ? 'selected' : '' }}>
                                            Gred {{ $w->gred }}: {{ $w->kelayakan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 mb-6">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                Mohon
                            </button>
                            <a href="{{ route('surat_akuan.index') }}"
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