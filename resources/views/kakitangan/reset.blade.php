<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Reset Password') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="w-full border border-collapse border-gray-300">
                        <tbody>
                            <tr class="bg-yellow-100">
                                <td class="p-2 font-bold border border-gray-300">Nama</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->nama }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold border border-gray-300">MyKAD</td>
                                <td class="p-2 border border-gray-300">{{ $kakitangan->mykad }}</td>
                            </tr>
                            <tr>
                                <input type="submit" value="Reset Password">
                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>