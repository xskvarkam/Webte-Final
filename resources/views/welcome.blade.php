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
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #1e293b;
            padding: 3rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            color: #cbd5e1;
            margin-bottom: 2rem;
        }

        .buttons a {
            margin: 0 0.5rem;
            display: inline-block;
            padding: 0.6rem 1.2rem;
            background-color: #3b82f6;
            color: #fff;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .buttons a:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to PDF Tools</h1>
    <p>A simple place to manage your PDF files. Please log in or register to get started.</p>
    <div class="buttons">
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    </div>
</div>
</body>
</html>
