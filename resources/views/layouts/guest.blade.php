<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="img/idslogo.png" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- LEFT SIDE: Logo & Green (hidden on small screens) -->
        <!-- LEFT SIDE: Logo & Green (hidden on small screens) -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center shadow-lg"
            style="background-color: rgb(0, 166, 82);">
            <div class="text-center px-4 py-6">
                <a href="/">
                    <!-- Custom Larger Logo -->
                    <x-application-logo class="w-[500px] h-auto mx-auto fill-current text-white" />
                    <h1 class="text-lg md:text-s font-bold uppercase mb-6 mt-4 text-white opacity-50">
                        A Global College in the Heart of Albay
                    </h1>

                </a>
            </div>
        </div>





        <!-- RIGHT SIDE: Form Section -->
        <div class="w-full md:w-1/2 bg-white flex items-center justify-center px-6 py-10">
            <div class="w-full max-w-md text-center">
                <!-- Logo -->
                <img src="{{ asset('img/idslogo.png') }}" alt="IDSC Logo" class="mx-auto w-24 md:w-32 mb-4">

                <!-- School Name -->
                <h1 class="text-xl md:text-2xl font-bold uppercase mb-6">
                    Infotech Development Systems Colleges Inc.
                </h1>

                <!-- Login Title -->
                <h2 class="text-green-600 text-lg md:text-xl font-bold uppercase mb-1">
                    Login to your account
                </h2>
                <p class="text-gray-500 text-sm mb-6">
                    Kindly provide your account credentials to continue.
                </p>
                <br>

                <!-- Original Login Form Slot -->
                {{ $slot }}
            </div>
        </div>

    </div>

</body>

</html>
