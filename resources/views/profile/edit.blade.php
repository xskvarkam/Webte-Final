<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @if (session('token'))
                <div class="mt-2 text-sm text-green-600">
                    Your API Token: <code>{{ session('token') }}</code>
                    <p class="text-xs text-red-500 mt-1">Copy this now. It won’t be shown again!</p>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.generate-token') }}">
                @csrf
                <button class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500 transition">
                    Generate New API Token
                </button>
            </form>
        </div>


    </div>
</x-app-layout>
