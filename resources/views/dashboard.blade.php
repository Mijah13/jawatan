<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($user)
                        <div class="mb-4">
                            <p class="text-lg font-semibold">Selamat datang, {{ $user->nama }}!</p>
                            <p class="text-sm text-gray-600">No. MyKad: <strong>{{ $user->mykad }}</strong></p>

                        </div>

                        <hr class="my-4">

                        <p class="text-base">Anda telah log masuk ke sistem.</p>
                    @else
                        <p class="text-red-600">Error: User data not found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
