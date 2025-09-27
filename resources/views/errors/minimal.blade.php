<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <style>
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #1a202c;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            color: #2563eb;
            margin: 0;
        }

        .error-message {
            font-size: 1.25rem;
            font-weight: 500;
            margin-top: 0.5rem;
            margin-bottom: 2rem;
            color: #4b5563;
        }

        a.button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background 0.2s ease-in-out;
        }

        a.button:hover {
            background-color: #1e40af;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">@yield('code')</div>
        <div class="error-message">@yield('message')</div>
        <a href="{{ route('dashboard') }}" class="button">Go back to Dashboard</a>
    </div>
</body>

</html>
