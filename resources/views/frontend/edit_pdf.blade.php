<x-app-layout>
    <x-slot name="header">
        <div style="
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    ">
            <!-- Left: Back button -->
            <a href="{{ route('pdf.index') }}" style="
            background-color: #4a5568;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        " onmouseover="this.style.backgroundColor='#2d3748'" onmouseout="this.style.backgroundColor='#4a5568'">
                ← Back
            </a>

            <!-- Center: Title -->
            <h2 style="
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
            margin: 0;
        ">
                Edit PDF Metadata
            </h2>

            <!-- Right: Invisible space filler to keep title centered -->
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

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #edf2f7;
            font-size: 1rem;
            background-color: #edf2f7; /* Light gray background */
            color: #1a202c; /* Dark text color */
        }

        input[type="file"] {
            padding: 0.5rem;
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
    </style>

    <div class="form-container">
        <form method="POST" action="{{ route('pdf.edit.process') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="pdf">Select PDF:</label>
                <input type="file" name="pdf" accept="application/pdf" required>
            </div>

            <div class="form-group">
                <label for="title">New Title:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">New Author:</label>
                <input type="text" name="author">
            </div>

            <button type="submit" class="submit-btn">Update Metadata</button>
        </form>
    </div>
</x-app-layout>
