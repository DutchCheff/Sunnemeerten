<div class="w-full h-screen bg-background absolute">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full flex justify-end items-center px-4 py-3 bg-inherit z-40 ">
        <div class="text-accent-light">
            <!-- naam -->
            <!-- Burger -->
            <button class=" " id="burger">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>
    </nav>
    <!-- slide out modal -->
    <div class="fixed top-0 right-0 h-full w-1/2 bg-card text-accent-light transform -translate-y-full transition-transform duration-1000 ease-in-out z-50"
        id="menu">
        <!-- closing button -->
        <div class="w-full flex justify-between">
            <button id="close-menu" class="">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div>
            <img src="/images/logo.png" alt="">
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
    <div class="mt-10">
        <img src="/images/logo.png" alt="">
    </div>

</div>