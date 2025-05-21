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
            background-color: white;
            color: 0 0 10px 4px rgba(49, 130, 206, 0.4);
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 160px;
        }

        .tool-card:hover {
            background-color: white;
            color: #1a202c;
            box-shadow: 0 0 10px 4px rgba(49, 130, 206, 0.4); /* soft blue outline */
            transform: scale(1.05);
        }

        .tool-icon {
            width: 64px;
            height: 64px;
            margin-bottom: 12px;
        }

        .tool-label {
            font-size: 1rem;
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
            $tools_icons = [
                ['key' => 'edit', 'route' => '/icons/edit.png'],
                ['key' => 'merge', 'route' => '/icons/merge.png'],
                ['key' => 'split', 'route' => '/icons/split.png'],
                ['key' => 'delete', 'route' => '/icons/delete.png'],
                ['key' => 'extract', 'route' => '/icons/extract.png'],
                ['key' => 'rotate', 'route' => '/icons/rotate.png'],
                ['key' => 'watermark', 'route' => '/icons/watermark.png'],
                ['key' => 'compress', 'route' => '/icons/compress.png'],
                ['key' => 'reverse', 'route' => '/icons/reverse.png'],
                ['key' => 'sign', 'route' => '/icons/sign.png'],
                ['key' => 'to_img', 'route' => '/icons/PDFtoImages.png'],
                ['key' => 'from_img', 'route' => '/icons/PDFfromImage.png']
            ];
        @endphp

        @foreach ($tools as $tool)
            @php
                $icon = collect($tools_icons)->firstWhere('key', $tool['key'])['route'] ?? null;
            @endphp

            <a href="{{ route($tool['route']) }}" class="tool-card">
                @if ($icon)
                    <img src="{{ asset($icon) }}" alt="{{ $tool['key'] }} icon" class="tool-icon">
                @endif
                <span class="tool-label">{{ __('pdf_tools.tools.' . $tool['key']) }}</span>
            </a>
        @endforeach


    </div>
</x-app-layout>
