<?php $flashes = takeFlashes(); ?>
<?php if ($flashes): ?>
    <div class="fixed top-20 left-0 w-full px-7 z-30 flex flex-col gap-2" id="flash">
        <?php foreach ($flashes as $flash): ?>
            <div class="<?= $flash['type'] === 'success'
                ? 'bg-green-100 border border-green-400 text-green-700'
                : 'bg-red-100 border border-red-400 text-red-700' ?> px-4 py-3 rounded shadow-md cursor-pointer" data-dismiss>
                <p><?= e($flash['message']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
