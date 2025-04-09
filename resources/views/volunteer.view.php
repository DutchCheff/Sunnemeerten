<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text flex flex-col items-center px-6 py-4">
    <h2 class="text-4xl font-semibold mb-4 mt-10">Kom helpen!</h2>
    <p class="my-5">Ben jij geïnteresseerd om ons team te versterken tijdens de parade? Meld je dan nu aan als
        vrijwilliger!</p>
    <form class="w-full">
        <div class="mb-4">
            <label class="block">Naam</label>
            <input type="text" class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="w-full flex justify-center">
            <button type="submit"
                class="bg-accent2 shadow-md text-text px-4 py-1 leading-[1.3] rounded-lg ">Aanmelden</button>
        </div>
    </form>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>