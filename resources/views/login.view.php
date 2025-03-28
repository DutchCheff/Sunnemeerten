<?php require_once '../resources/views/header.view.php'; ?>
<div class="w-full h-screen bg-background absolute">
    <!-- Navbar -->
    <nav
        class="fixed top-0 left-0 w-full flex justify-between items-center px-4 py-3 bg-inherit z-40 text-accent-light">
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
            <a href="" class="text-3xl">
                <p class="text-shadow">Account</p>
            </a>
            <a href="" class="text-3xl">
                <p class="text-shadow">Doneren</p>
            </a>
            <a href="" class="text-3xl">
                <p class="text-shadow">Vrijwilligers
                </p>
            </a>
        </div>

    </div>
    <!-- Logo -->
    <div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4">
        <div class="text-center pb-5">
            <h1 class="text-4xl">De parade begint over</h1>
        </div>
        <div class="flex gap-3 justify-between">
            <div class="flex flex-col gap-2 justify-center items-center">
                <h1 class="text-3xl">221</h1>
                <p>Dagen</p>
            </div>
            <div class="flex flex-col gap-2 justify-center items-center">
                <h1 class="text-3xl">18</h1>
                <p>Uur</p>
            </div>
            <div class="flex flex-col gap-2 justify-center items-center">
                <h1 class="text-3xl">48</h1>
                <p>Minuten</p>
            </div>
            <div class="flex flex-col gap-2 justify-center items-center">
                <h1 class="text-3xl">35</h1>
                <p>Seconden</p>
            </div>
        </div>
    </div>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>