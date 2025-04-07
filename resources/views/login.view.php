<?php require_once '../resources/views/header.view.php'; ?>



    <!-- Logo -->
    <div class="mt-14">
        <img src="/images/logo1.png" alt="" class="scale-90">
    </div>
    <div class="bg-card mx-7 rounded-2xl text-text px-3 py-4">
        <div>
            <h1 class="text-4xl text-center">Login</h1>
            <a href="?page=register">Registreren</a>
        </div>
        <form action="sign-in">
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email"
                    class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>

            <div class="mb-4">
                <label class="block">Wachtwoord</label>
                <input type="password"
                    class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>
            <div class="flex justify-between">
                <div>
                    <button>
                        Gast
                    </button>
                </div>
                <div>
                    <button>
                        Registreren
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php require_once '../resources/views/footer.view.php'; ?>