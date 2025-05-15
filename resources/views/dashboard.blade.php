<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8 text-center text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">{{ __("You're logged in!") }}</h1>

                <p class="mb-6 text-lg text-gray-700 dark:text-gray-300">
                    Use the button below to access the PDF tools.
                </p>

                <a href="{{ route('pdf.index') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    ➤ Go to PDF Tools
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
