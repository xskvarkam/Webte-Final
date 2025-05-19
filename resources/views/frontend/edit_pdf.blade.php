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
                {{ __('edit_pdf.back') }}
            </a>

            <h2 style="
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                font-size: 1.5rem;
                color: white;
                font-weight: bold;
                margin: 0;
                text-align: center;
                line-height: 1.4;
                padding: 0 1rem;
                max-width: 90%;
                word-break: break-word;
            ">
                {{ __('edit_pdf.title') }}
            </h2>

            <div style="width: 85px;"></div>
        </div>
        <style>
            @media (max-width: 640px) {
                h2 {
                    font-size: 1.25rem !important;
                }
            }
        </style>
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
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #edf2f7;
            color: #1a202c;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }

        .submit-btn {
            background-color: #3182ce;
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
            background-color: #2b6cb0;
        }

        @media (max-width: 640px) {
            .submit-btn {
                width: 100%;
            }
        }
    </style>

    <div class="form-container">
        <form method="POST" action="{{ route('pdf.edit.process') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group" x-data="{ fileName: '' }">
                <label for="pdf" class="mb-2 block">{{ __('edit_pdf.select_pdf') }}</label>

                <div class="upload-row bg-gray-100 border border-gray-300 rounded px-3 py-2">
                    <button type="button"
                            @click="$refs.fileInput.click()"
                            class="custom-upload-btn">
                        {{ __('edit_pdf.choose_button') }}
                    </button>

                    <span x-text="fileName || '{{ __('edit_pdf.no_file') }}'" class="file-info"></span>
                </div>

                <input type="file" id="pdf" name="pdf" accept="application/pdf" required
                       class="hidden"
                       x-ref="fileInput"
                       @change="fileName = $refs.fileInput.files[0]?.name || ''" />
            </div>

            <div class="form-group">
                <label for="title">{{ __('edit_pdf.new_title') }}</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">{{ __('edit_pdf.new_author') }}</label>
                <input type="text" id="author" name="author">
            </div>

            <button type="submit" class="submit-btn">
                {{ __('edit_pdf.submit') }}
            </button>
        </form>
    </div>
</x-app-layout>
