<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Tools</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem; /* aby sa to nelepilo na okraj */
            box-sizing: border-box;
        }

        .container {
            text-align: center;
            background-color: #1e293b;
            padding: 3rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .lang-switch {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        .lang-switch a {
            color: #fff;
            font-size: 0.9rem;
            margin-left: 0.5rem;
            text-decoration: underline;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
            word-break: break-word;
        }

        p {
            font-size: 1rem;
            color: #cbd5e1;
            margin-bottom: 2rem;
            padding: 0 0.5rem;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
        }

        .buttons a {
            display: block;
            width: 100%;
            padding: 0.6rem 1.2rem;
            background-color: #3b82f6;
            color: #fff;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s ease;
        }

        .buttons a:hover {
            background-color: #2563eb;
        }

        @media (min-width: 500px) {
            .buttons {
                flex-direction: row;
                justify-content: center;
                gap: 1rem;
            }

            .buttons a {
                width: auto;
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="lang-switch">
        <a href="{{ route('set-locale', ['locale' => 'sk']) }}">| SK |</a>
        <a href="{{ route('set-locale', ['locale' => 'en']) }}">| EN |</a>
    </div>

    <h1>{{ __('welcome.title') }}</h1>
    <p>{{ __('welcome.description') }}</p>
    <div class="buttons">
        <a href="{{ route('login') }}">{{ __('welcome.login') }}</a>
        <a href="{{ route('register') }}">{{ __('welcome.register') }}</a>
    </div>
</div>
</body>
</html>
