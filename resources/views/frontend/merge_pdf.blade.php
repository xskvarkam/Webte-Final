<x-app-layout>
    <x-slot name="header">
        <div style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        ">
            <!-- Back Button -->
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

            <!-- Centered Title -->
            <h2 style="
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                font-size: 1.5rem;
                color: white;
                font-weight: bold;
                margin: 0;
            ">
                {{ __('pdf_merge.title') }}
            </h2>

            <!-- Right Spacer -->
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
            margin: 2rem auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #edf2f7;
            color: #1a202c;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #48bb78;
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.4);
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
    </style>

    <div class="form-container">
        <form method="POST" action="{{ route('pdf.merge.process') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="pdf1">{{ __('pdf_merge.first_pdf') }}</label>
                <input type="file" name="pdf1" accept="application/pdf" required>
            </div>

            <div class="form-group">
                <label for="pdf2">{{ __('pdf_merge.second_pdf') }}</label>
                <input type="file" name="pdf2" accept="application/pdf" required>
            </div>

            <button type="submit" class="submit-btn">
                {{ __('pdf_merge.submit') }}
            </button>
        </form>
    </div>
</x-app-layout>
