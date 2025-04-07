<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4">
    <h2 class="text-2xl font-semibold text-center mb-4">Kom helpen!</h2>
    <p class="my-5">Ben jij geïnteresseerd om ons team te versterken tijdens de parade? Meld je dan nu aan als vrijwilliger!</p>
    <form>
        <div class="mb-4">
            <label class="block">Naam</label>
            <input type="text" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-accent2 text-text px-4 py-2 rounded-lg hover:bg-blue-600">Aanmelden</button>
        </div>
    </form>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>