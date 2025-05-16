<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 2rem; text-align: center; color: white; font-weight: bold;">
            PDF Tools
        </h2>
    </x-slot>

    <style>
        body {
            background-color: #1a202c;
        }

        .tool-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .tool-card {
            background-color: #2d3748;
            color: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 160px;
        }

        .tool-card:hover {
            background-color: #3182ce;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="tool-grid">
        @php
            $tools = [
                ['title' => 'Edit Metadata', 'route' => 'pdf.edit'],
                ['title' => 'Merge PDFs', 'route' => 'pdf.merge'],
                ['title' => 'Split PDF', 'route' => 'pdf.split'],
                ['title' => 'Delete Pages', 'route' => 'pdf.delete'],
                ['title' => 'Extract Text', 'route' => 'pdf.extract'],
                ['title' => 'Rotate Pages', 'route' => 'pdf.rotate'],
                ['title' => 'Add Watermark', 'route' => 'pdf.watermark'],
                ['title' => 'Compress PDF(NEFUNGUJE)', 'route' => 'pdf.compress'],
                ['title' => 'Reverse Pages', 'route' => 'pdf.reverse'],
                ['title' => 'Sign PDF', 'route' => 'pdf.sign'],
            ];
        @endphp

        @foreach ($tools as $tool)
            <a href="{{ route($tool['route']) }}" class="tool-card">
                {{ $tool['title'] }}
            </a>
        @endforeach
    </div>
</x-app-layout>
