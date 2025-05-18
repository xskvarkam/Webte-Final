<x-app-layout>
    <x-slot name="header">
        <div style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        ">
            <a href="{{ route('pdf.index') }}" style="
                background-color: #4a5568;
                color: white;
                padding: 0.4rem 1rem;
                border-radius: 6px;
                text-decoration: none;
                font-weight: bold;
                transition: background-color 0.3s ease;
            " onmouseover="this.style.backgroundColor='#2d3748'" onmouseout="this.style.backgroundColor='#4a5568'">
                {{ __('pdf_merge.back') }}
            </a>

            <h2 style="
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                font-size: 1.5rem;
                color: white;
                font-weight: bold;
                margin: 0;
                text-align: center;
                white-space: normal;
            ">
                {{ __('pdf_merge.title') }}
            </h2>

            <div style="width: 85px;"></div>
        </div>
    </x-slot>

    <style>
        .form-container {
            background-color: #2d3748;
            color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 96%;
            margin: 2rem auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .custom-upload-btn {
            background-color: #4a5568 !important;
            color: white !important;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: bold;
            transition: 0.3s ease;
            white-space: nowrap;
        }

        .custom-upload-btn:hover {
            background-color: #2d3748 !important;
        }

        .submit-btn {
            background-color: #38a169;
            color: white;
            font-weight: bold;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #2f855a;
        }

        .upload-row {
            display: flex;
            gap: 1rem;
            flex-wrap: nowrap;
            align-items: center;
            overflow-x: auto;
        }

        .file-info {
            background-color: #edf2f7;
            color: #1a202c;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            border: 1px solid #ccc;
            white-space: nowrap;
            min-width: 0;
            flex-grow: 1;
        }

        @media (max-width: 640px) {
            .submit-btn {
                width: 100%;
            }
        }
    </style>

    <div class="form-container">
        <form method="POST" action="{{ route('pdf.merge.process') }}" enctype="multipart/form-data">
            @csrf

            {{-- PDF 1 --}}
            <div class="form-group" x-data="{ fileName1: '' }">
                <label class="mb-2 block">{{ __('pdf_merge.first_pdf') }}</label>
                <div class="upload-row bg-gray-100 border border-gray-300 rounded px-3 py-2">
                    <button type="button"
                            @click="$refs.fileInput1.click()"
                            class="custom-upload-btn">
                        {{ __('pdf_merge.choose_button') }}
                    </button>
                    <span x-text="fileName1 || '{{ __('pdf_merge.no_file') }}'" class="file-info truncate"></span>
                </div>
                <input type="file" name="pdf1" accept="application/pdf" required
                       class="hidden"
                       x-ref="fileInput1"
                       @change="fileName1 = $refs.fileInput1.files[0]?.name || ''" />
            </div>

            {{-- PDF 2 --}}
            <div class="form-group" x-data="{ fileName2: '' }">
                <label class="mb-2 block">{{ __('pdf_merge.second_pdf') }}</label>
                <div class="upload-row bg-gray-100 border border-gray-300 rounded px-3 py-2">
                    <button type="button"
                            @click="$refs.fileInput2.click()"
                            class="custom-upload-btn">
                        {{ __('pdf_merge.choose_button') }}
                    </button>
                    <span x-text="fileName2 || '{{ __('pdf_merge.no_file') }}'" class="file-info truncate"></span>
                </div>
                <input type="file" name="pdf2" accept="application/pdf" required
                       class="hidden"
                       x-ref="fileInput2"
                       @change="fileName2 = $refs.fileInput2.files[0]?.name || ''" />
            </div>

            <button type="submit" class="submit-btn">
                {{ __('pdf_merge.submit') }}
            </button>
        </form>
    </div>
</x-app-layout>
