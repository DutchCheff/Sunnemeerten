<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/43032dec1c.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Merriweather:ital,wght@0,900;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="/js/script.js" defer></script>
    <title>Sunnemeerten</title>
</head>

<body class="josefin-sans-bold bg-<?php
            if (isset($_GET['page'])) {
                if ($_GET['page'] == "test") { echo 'accent2' ; } else { echo 'background' ; } } ?>">
</body>
<nav class="fixed top-0 left-0 w-full flex justify-between items-center px-4 py-3 z-40 bg-inherit text-accent-light">
    <!-- naam -->
    <div class="w-32">
        <img src="/images/logo3-light.png" alt="" class="">
    </div>
    <!-- Burger -->
    <button class="w-14" id="burger">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
            </path>

        </svg>
    </button>
</nav>
<!-- slide out modal -->
<div class="fixed top-0 right-0 h-full w-1/2 bg-card text-accent-light transform translate-x-full transition-transform duration-700 ease-in-out z-50"
    id="menu">
    <!-- closing button -->
    <button id="close-menu" class="absolute top-0 right-0 px-4 py-3">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </button>
    <div class="">
        <img src="/images/logo.png" alt="" class="mt-10">
    </div>
    <div class="flex flex-col gap-3 mt-3 px-2">
        <a href="" aria-current="page" class="text-3xl text-accent2">
            <p class="text-shadow">Home</p>
        </a>
        <a href="" class="text-3xl">
            <p class="text-shadow">Foto's</p>
        </a>
        <a href="" class="text-3xl">
            <p class="text-shadow">Route</p>
        </a>
        <a href="?page=account" class="text-3xl">
            <p class="text-shadow">Account</p>
        </a>
        <a href="?page=donation" class="text-3xl">
            <p class="text-shadow">Doneren</p>
        </a>
        <a href="?page=volunteer" class="text-3xl">
            <p class="text-shadow">Vrijwilligers
            </p>
        </a>
    </div>

</div>

</html>