<?php if (!empty($post['images'])): ?>
    <div class="flex gap-2 overflow-x-auto snap-x snap-mandatory mt-3 rounded-lg">
        <?php foreach ($post['images'] as $image): ?>
            <a href="/<?= e($image) ?>" target="_blank" class="snap-center shrink-0 <?= count($post['images']) > 1 ? 'w-11/12' : 'w-full' ?>">
                <img src="/<?= e($image) ?>" alt="<?= e($post['title']) ?>" loading="lazy" class="w-full h-auto rounded-lg">
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
