<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('PDF Tools') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h1>{{ __('messages.pdf_tools') }}</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('pdf.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="pdf">Upload a PDF:</label>
                    <input type="file" name="pdf" id="pdf" accept="application/pdf" required>
                    <button type="submit">Upload</button>
                </form>

                @if(session('file'))
                    <p>Uploaded File: <strong>{{ session('file') }}</strong></p>
                @endif

                <ul class="mt-6 list-disc list-inside">
                    <li>1. Merge PDFs (placeholder)</li>
                    <li>2. Split PDF (placeholder)</li>
                    <li>3. Delete Pages (placeholder)</li>
                    <li>4. Extract Text (placeholder)</li>
                    <li>5. Compress PDF (placeholder)</li>
                    <li>6. Rotate Pages (placeholder)</li>
                    <li>7. Watermark PDF (placeholder)</li>
                    <li>8. Convert to Word (placeholder)</li>
                    <li>9. OCR (Text Recognition) (placeholder)</li>
                    <li>10. Sign PDF (placeholder)</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
