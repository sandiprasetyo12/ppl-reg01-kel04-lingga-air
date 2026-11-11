<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'LinggaAir'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'class' };
        </script>
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
        :focus-visible {
            outline: 2px solid #1b1b18;
            outline-offset: 2px;
        }
        a[href], button, select, summary, label[for], input[type="checkbox"], input[type="radio"] {
            cursor: pointer;
        }
        button:disabled, input:disabled, select:disabled {
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] antialiased min-h-screen flex flex-col">
    @yield('header')

    <main class="flex-1 flex flex-col">
        @if (session('success'))
            <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 pt-4">
                <div role="status" class="p-3.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 pt-4">
                <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @yield('footer')
    @stack('scripts')
</body>
</html>