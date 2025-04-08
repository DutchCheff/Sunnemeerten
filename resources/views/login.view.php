<?php require_once '../resources/views/header.view.php'; ?>



<div class="bg-inherit">
    <div class="mt-16 mb-10">
        <img src="/images/logo1.png" alt="" class="scale-90">
    </div>
    <div class="bg-card shadow-md mx-7 mb-4 rounded-2xl text-text px-3 py-4">
        <div>
            <h1 class="text-4xl text-center">Login</h1>
        </div>
        <form action="sign-in">
            <div class="mb-4 mt-3">
                <label class="">Email</label>
                <input type="email"
                    class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>

            <div class="mb-4">
                <label class="block">Wachtwoord</label>
                <input type="password"
                    class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>
            <div>
                <button class="bg-accent1 text-text px-4 py-2 rounded-lg w-full mb-4 mt-4 text-base">
                    Inloggen
                </button>
            </div>
            <div class="flex gap-2 w-full">
                <div class="w-1/2">
                    <button class="bg-accent2 text-text px-4 py-2 rounded-lg w-full mb-4 mt-4 text-base">
                        Gast
                    </button>
                </div>
                <div class="w-1/2">
                    <button class="bg-background text-text px-4 py-2 rounded-lg w-full mb-4 mt-4 text-base">
                        Registreren
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>