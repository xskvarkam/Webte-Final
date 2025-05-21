<x-app-layout>
    <x-slot name="header">
        <div style="
            display: flex;
            justify-content: center;
            align-items: center;
        ">
            <h2 style="
                font-size: 1.75rem;
                color: white;
                font-weight: bold;
                margin: 0;
            ">
                {{ __('dashboard.title') }}
            </h2>
        </div>
    </x-slot>

    <style>
        .dashboard-box {
            background-color: #2d3748;
            color: white;
            padding: 2.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin: 2rem auto;
            box-sizing: border-box;
        }

        .dashboard-box h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .dashboard-box p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #e2e8f0;
            padding: 0 1rem;
        }

        .dashboard-box a {
            display: inline-block;
            background-color: #3182ce;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            max-width: 100%;
            word-break: break-word;
            text-align: center;

        }

        .dashboard-box a:hover {
            background-color: #2b6cb0;
        }

        @media (max-width: 640px) {
            .dashboard-box {
                margin: 1.25rem 1rem;
                padding: 1.5rem 1rem;
            }

            .dashboard-box h1 { 
                font-size: 1.5rem;
            }

            .dashboard-box p {
                font-size: 1rem;
            }

            .dashboard-box a {
                display: block;
                width: 100%;
                text-align: center;
            }
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-box {
            background: white;
            color: black;
            padding: 2rem;
            border-radius: 12px;
            max-width: 900px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto; 
            position: relative;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4);
        }

        .modal-box p { text-align: left; }

        .modal-close {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .modal-close:hover {
            color: #000;
        }
    </style>


    <div class="dashboard-box">
        <h1>{{ __('dashboard.heading') }}</h1>
        <p>{{ __('dashboard.description') }}</p>
        <a href="{{ route('pdf.index') }}">➤ {{ __('dashboard.button') }}</a>

    </div>
    <div class="dashboard-box">
        <h1>{{ __('dashboard.docs_heading') }}</h1>
        <p>{{ __('dashboard.docs_description') }}</p>
        <a href="/docs">➤ {{ __('dashboard.docs_button') }}</a>
    </div>
    <div class="dashboard-box" x-data="{ showModal: false }">
        <h1>{{ __('dashboard.instructions_heading') }}</h1>
        <p>{{ __('dashboard.instructions_description') }}</p>
    
        <a @click="showModal = true" style="margin-top: 1rem; color: white; cursor: pointer;">
            ➤ {{ __('dashboard.instructions_button') }}
        </a>
    
        <!-- Modal Overlay -->
        <div x-show="showModal" x-cloak class="modal-overlay" @keydown.escape.window="showModal = false">
            <!-- Modal Box -->
            <div class="modal-box" @click.away="showModal = false">
                <button @click="showModal = false" class="modal-close">&times;</button>
                <h1>{{ __('dashboard.instructions_heading') }}:</h1>
                <div id="instructions-content">
                    <p style="color: black;">{{ __('dashboard.instructions_edit') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_merge') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_split') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_delete') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_extract') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_rotate') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_watermark') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_compress') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_reverse') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_sign') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_PDFtoImg') }}</p>
                    <p style="color: black;">{{ __('dashboard.instructions_PDFfromImg') }}</p>
                </div>
                <button
                    id="download-instructions"
                    style="margin-top: 1.5rem; background: #3182ce; color: white; border: none; border-radius: 8px; padding: 0.5rem 1.2rem; font-weight: bold; cursor: pointer;"
                    type="button"
                >
                    {{ __('dashboard.download_instructions_button') ?? 'Download Instructions as PDF' }}
                </button>
            </div>
            
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            // Wait for Alpine to be ready
            document.getElementById('download-instructions')?.addEventListener('click', function () {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
    
                // Get the instructions
                const instructions = Array.from(
                    document.querySelectorAll('#instructions-content p')
                ).map(p => p.textContent);
    
                // Title
                doc.setFontSize(18);
                doc.text(
                    "{{ __('dashboard.instructions_heading') }}",
                    105,
                    20,
                    { align: 'center' }
                );
    
                // Instructions
                doc.setFontSize(12);
                let y = 35;
                instructions.forEach((line, idx) => {
                    doc.text(`• ${line}`, 20, y);
                    y += 10;
                    // Add new page if needed
                    if (y > 270) {
                        doc.addPage();
                        y = 20;
                    }
                });
    
                doc.save('instructions.pdf');
            });
        });
    </script>
    
    
    
</x-app-layout>

