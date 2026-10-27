<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @vite('resources/js/app.js')
    <x-inertia::head />
</head>

<body>
    <x-inertia::app />
</body>

</html>