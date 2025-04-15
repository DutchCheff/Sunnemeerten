<?php require_once '../public/database/auth.php'; ?>
<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card h-[400px] mx-7 mt-28 rounded-2xl text-text px-3 py-4 flex flex-col">
    <h2 class="text-2xl font-semibold text-center mb-4">Doneer nu!</h2>
    <p class="p-5 my-5 text-text">Wordt jij net zo enthousiast als ons over dit project? Draag dan nu je steentje bij!!</p>
    <div class="mb-2 flex justify-center mt-auto">
        <a class="bg-accent2 text-text px-4 py-2 rounded-lg hover:bg-blue-600" href="?page=donation">Doneer</a>
    </div>


<div class="h-screen flex items-center outl">
    <div class="bg-background shadow-md mx-7 mt-28 rounded-2xl text-text px-3 py-16 flex flex-col">
        <div>
            <h1 class="text-4xl font-semibold text-center mb-4">Doneer nu!</h1>
            <p class="p-5 my-5 text-text">
                Wordt jij net zo enthousiast als ons over dit project? Draag dan nu je steentje bij!!
            </p>
        </div>
        <div class="mb-2 flex justify-center mt-auto">
            <a class="bg-accent2 shadow-md text-text px-4 py-2 rounded-lg text-lg" href="?page=donation">Doneer</a>
        </div>
    </div>

</div>
<?php require_once '../resources/views/footer.view.php'; ?>