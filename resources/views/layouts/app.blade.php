<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Video game aggregator</title>
    <link rel="stylesheet" href="/css/main.css">
    <livewire:styles />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

</head>
<body class="bg-gray-900 text-white">
    <header class="border-b border-gray-800">
        <nav class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">
            <div class="flex flex-col lg:flex-row items-center">
                <a href="#" class="text-4xl">Game<span class="text-green-600 font-extrabold">Info</span></a>
                <ul class="flex ml-0 lg:ml-16 space-x-8 mt-7 lg:mt-0">
                    <li><a href="/" class="hover:text-gray-400">Strona główna</a></li>
                </ul>
            </div>
            <div class="flex items-center mt-7 lg:mt-0">
                <livewire:search-dropdown>
            </div>
        </nav>
    </header>

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="border-r border-gray-800">
        <div class="container mx-auto px-4 py-6 flex justify-center">
            Paweł Mróz 2021. Wszystkie prawa zastrzeżone
        </div>
    </footer>

    <livewire:scripts />
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
