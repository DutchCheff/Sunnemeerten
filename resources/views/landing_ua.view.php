<?php
if (isset($_GET['guest'])) {
    $_SESSION['guest'] = true;
    redirect('?page=landing');
}
if (isLoggedIn()) {
    redirect('?page=landing');
}
?>
<?php require VIEW_PATH . '/header.view.php'; ?>
<div class="mt-16">
    <div>
        <img src="/images/logo1.png" alt="logo sunnemeerten" class="mx-auto max-w-md w-full">
    </div>
    <div class="flex flex-col gap-3 items-center mt-8 w-full">
        <div>
            <a href="?page=login" class="block bg-card shadow-md px-8 py-1 leading-[1.3] text-2xl text-text rounded-lg">
                Log In
            </a>
        </div>
        <div>
            <a href="?page=landing_ua&guest=1" class="block bg-accent2 shadow-md px-8 py-1 leading-[1.3] text-2xl text-text rounded-lg">
                Gast
            </a>
        </div>
    </div>
</div>
<?php require VIEW_PATH . '/footer.view.php'; ?>
