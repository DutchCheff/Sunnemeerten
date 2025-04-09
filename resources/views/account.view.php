<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text px-3 py-4">
    <h2 class="text-4xl font-semibold text-center my-4">Account Wijzigen</h2>
    <form>
        <div class="mb-4">
            <label class="block">Wijzig Naam</label>
            <input type="text"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Wijzig Email</label>
            <input type="email"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Wijzig Wachtwoord</label>
            <input type="password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Herhaal wachtwoord</label>
            <input type="password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex gap-2">
            <button type="button"
                class="bg-accent2 w-1/2 text-text px-4 py-1 leading[1.3] rounded-lg shadow-md">Verwijderen</button>
            <a class="bg-accent1 w-1/2 text-text px-4 py-1 leading[1.3] rounded-lg shadow-md"
                href="?page=login">Afmelden</a>
        </div>
    </form>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>