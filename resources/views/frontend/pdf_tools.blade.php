<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 2rem; text-align: center; color: white; font-weight: bold;">
            {{ __('pdf_tools.title') }}
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
                ['key' => 'edit', 'route' => 'pdf.edit'],
                ['key' => 'merge', 'route' => 'pdf.merge'],
                ['key' => 'split', 'route' => 'pdf.split'],
                ['key' => 'delete', 'route' => 'pdf.delete'],
                ['key' => 'extract', 'route' => 'pdf.extract'],
                ['key' => 'rotate', 'route' => 'pdf.rotate'],
                ['key' => 'watermark', 'route' => 'pdf.watermark'],
                ['key' => 'compress', 'route' => 'pdf.compress'],
                ['key' => 'reverse', 'route' => 'pdf.reverse'],
                ['key' => 'sign', 'route' => 'pdf.sign'],
                ['key' => 'to_img', 'route' => 'pdf.to_img'],
                ['key' => 'from_img', 'route' => 'pdf.from_img']
            ];
        @endphp

        @foreach ($tools as $tool)
            <a href="{{ route($tool['route']) }}" class="tool-card">
                {{ __('pdf_tools.tools.' . $tool['key']) }}
            </a>
        @endforeach
    </div>
</x-app-layout>
