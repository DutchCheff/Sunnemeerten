<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text px-6 py-4">
    <h2 class="text-4xl font-semibold text-center mb-4">Registreren</h2>
    <form>
        <div class="mb-4">
            <label class="block">Naam</label>
            <input type="text"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Wachtwoord</label>
            <input type="password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label class="block">Herhaal wachtwoord</label>
            <input type="password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-center gap-2">
            <button type="button"
                class="bg-accent2 shadow-md text-text px-4 py-1 leading-[1.3] rounded-lg w-1/2">Voorwaarden</button>
            <button type="submit" class="bg-accent1 shadow-md text-text px-4 py-1 leading-[1.3] rounded-lg w-1/2">Sign
                Up</button>
        </div>
    </form>
</div>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>