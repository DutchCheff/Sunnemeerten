<?php
$adminTabs = [
    'admin' => "Foto's",
    'admin_news' => 'Nieuws',
    'admin_workshops' => 'Werkplaatsen',
    'admin_route' => 'Route',
    'tracker' => 'Live GPS',
    'admin_users' => 'Gebruikers',
    'admin_volunteers' => 'Vrijwilligers',
];
$pendingCount = countPosts('pending');
?>
<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h1 class="text-4xl font-semibold text-center mb-4">Beheer</h1>
    <nav class="grid grid-cols-2 sm:grid-cols-4 gap-2" aria-label="Beheer">
        <?php foreach ($adminTabs as $tab => $label): ?>
            <a href="?page=<?= $tab ?>"
               class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-center <?= currentPage() === $tab ? 'bg-accent1' : 'bg-background' ?>"
                <?= currentPage() === $tab ? 'aria-current="page"' : '' ?>>
                <?= e($label) ?>
                <?php if ($tab === 'admin' && $pendingCount > 0): ?>
                    <span class="bg-accent2 text-text text-xs rounded-full px-2 py-0.5" title="Wachten op goedkeuring"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
</div>
