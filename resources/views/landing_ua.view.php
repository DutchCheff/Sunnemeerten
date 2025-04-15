<?php require_once '../public/database/auth.php'; ?>
<?php require_once '../resources/views/header.view.php'; ?>
<div class="mt-16">
    <div>
        <img src="/images/logo1.png" alt="logo sunnemeerten">
    </div>
    <div class="flex flex-col gap-3 items-center mt-8 w-full">
        <div>
            <button class="bg-card shadow-md px-8 py-1 leading-[1.3] text-2xl text-text rounded-lg">
                <a href="?page=login">Log In</a>
            </button>
        </div>
        <div>
            <button class="bg-accent2 shadow-md px-8 py-1 leading-[1.3] text-2xl text-text rounded-lg">
                <a href="?page=landing">Gast</a>
            </button>
        </div>
    </div>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>