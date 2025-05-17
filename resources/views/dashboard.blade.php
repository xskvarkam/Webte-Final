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
        }

        .dashboard-box a {
            background-color: #3182ce;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .dashboard-box a:hover {
            background-color: #2b6cb0;
        }
    </style>

    <div class="dashboard-box">
        <h1>{{ __('dashboard.heading') }}</h1>
        <p>{{ __('dashboard.description') }}</p>
        <a href="{{ route('pdf.index') }}">➤ {{ __('dashboard.button') }}</a>
    </div>
</x-app-layout>
