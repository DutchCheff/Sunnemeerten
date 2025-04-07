<?php require_once '../resources/views/header.view.php'; ?>
<div class="w-full h-screen bg-background absolute">
    <div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4">
        <h2 class="text-2xl font-semibold text-center mb-4">Registreren</h2>
        <form>
            <div class="mb-4">
                <label class="block">Naam</label>
                <input type="text" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block">Wachtwoord</label>
                <input type="password" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block">Herhaal wachtwoord</label>
                <input type="password" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-between">
                <button type="button" class="bg-accent2 text-text px-4 py-2 rounded-lg hover:bg-gray-400">Voorwaarden</button>
                <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg hover:bg-blue-600">Sign Up</button>
            </div>
        </form>
    </div>
</div>