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
                {{ __('pdf_rotate.back') }}
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
                {{ __('pdf_rotate.title') }}
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

        input[type="text"], select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #edf2f7;
            color: #1a202c;
            font-size: 1rem;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #ecc94b;
            box-shadow: 0 0 0 3px rgba(236, 201, 75, 0.4);
        }

        .submit-btn {
            background-color: #d69e2e;
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
            background-color: #b7791f;
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

        .error-box {
            background-color: #fed7d7;
            color: #c53030;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 640px) {
            .submit-btn {
                width: 100%;
            }
        }
    </style>

    <div class="form-container">
        @if($errors->any())
            <div class="error-box">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pdf.rotate.process') }}" enctype="multipart/form-data">
            @csrf

            {{-- File upload --}}
            <div class="form-group" x-data="{ fileName: '' }">
                <label class="mb-2 block">{{ __('pdf_rotate.select_pdf') }}</label>

                <div class="upload-row bg-gray-100 border border-gray-300 rounded px-3 py-2">
                    <button type="button"
                            @click="$refs.fileInput.click()"
                            class="custom-upload-btn">
                        {{ __('pdf_rotate.choose_button') }}
                    </button>

                    <span x-text="fileName || '{{ __('pdf_rotate.no_file') }}'" class="file-info truncate"></span>
                </div>

                <input type="file" name="pdf" accept="application/pdf" required
                       class="hidden"
                       x-ref="fileInput"
                       @change="fileName = $refs.fileInput.files[0]?.name || ''" />
            </div>

            {{-- Page range --}}
            <div class="form-group">
                <label for="pages">{{ __('pdf_rotate.pages_label') }}</label>
                <input type="text" name="pages" placeholder="{{ __('pdf_rotate.pages_placeholder') }}" required>
            </div>

            {{-- Rotation angle --}}
            <div class="form-group">
                <label for="angle">{{ __('pdf_rotate.angle_label') }}</label>
                <select name="angle" required>
                    <option value="90">90°</option>
                    <option value="180">180°</option>
                    <option value="270">270°</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">
                {{ __('pdf_rotate.submit') }}
            </button>
        </form>
    </div>
</x-app-layout>
