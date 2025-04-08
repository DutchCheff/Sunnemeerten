<?php require_once '../resources/views/header.view.php'; ?>
<div class="mt-16">
    <img src="/images/logo1.png" alt="logo">
</div>
<div class="flex flex-col gap-8 items-center mt-8 mx-7">
    <div>
        <button class="bg-card shadow-md px-8 py-1 leading-[1.3] text-4xl text-text rounded-lg">
            <a href="?page=route">Route</a>
        </button>
    </div>
    <div class="bg-card shadow-md rounded-lg p-1 w-full">
        <div class="p-2">
            <img src="/images/route-placeholder.png" alt="example image" class="w-full h-auto rounded-lg">
        </div>
    </div>
</div>
<div class="flex flex-col gap-8 items-center mt-24 mx-7">
    <div>
        <button class="bg-accent1 shadow-md px-8 py-1 leading-[1.3] text-4xl text-text rounded-lg">
            <a href="?page=route">Foto's</a>
        </button>
    </div>
    <div class="bg-card shadow-md rounded-lg flex flex-col gap-2 items-center w-full mx-7">
        <div class="px-3 pt-4">
            <img src="/images/11.jpg" alt="foto" class="rounded-lg">
        </div>
        <div class="px-3">
            <img src="/images/12.jpg" alt="" class="rounded-lg">
        </div>
        <div class="px-3">
            <img src="/images/13.jpg" alt="" class="rounded-lg">
        </div>
        <div class="px-3 pb-4">
            <img src="/images/14.jpg" alt="" class="rounded-lg">
        </div>
    </div>
</div>
<div>
    <button>
        <a href="?page=news"></a>
    </button>
    <button>
        <a href="?page=volunteer"></a>
    </button>
    <button>
        <a href="?page=donation"></a>
    </button>
</div>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>