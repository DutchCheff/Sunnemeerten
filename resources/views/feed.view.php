<?php
$pageNumber = max(1, (int) ($_GET['p'] ?? 1));
$feedUrl = '?page=feed' . ($pageNumber > 1 ? '&p=' . $pageNumber : '');

if (isPost()) {
    verifyCsrf();

    if (!isLoggedIn()) {
        flash('error', 'Log in om te liken of te reageren.');
        redirect('?page=login');
    }

    $userId = currentUser()['id'];
    $postId = (int) ($_POST['post_id'] ?? 0);
    $post = dbOne("SELECT id FROM posts WHERE id = ? AND status = 'approved'", [$postId]);

    if (!$post) {
        flash('error', 'Deze post bestaat niet meer.');
        redirect($feedUrl);
    }

    switch ($_POST['action'] ?? '') {
        case 'like':
            if (!dbRun('DELETE FROM likes WHERE user_id = ? AND post_id = ?', [$userId, $postId])) {
                dbRun('INSERT INTO likes (user_id, post_id, created_at) VALUES (?, ?, ?)', [$userId, $postId, now()]);
            }
            break;

        case 'comment':
            $body = trim($_POST['body'] ?? '');
            if ($body === '' || mb_strlen($body) > 500) {
                flash('error', 'Een reactie moet tussen de 1 en 500 tekens lang zijn.');
            } else {
                dbRun('INSERT INTO comments (post_id, user_id, body, created_at) VALUES (?, ?, ?, ?)', [$postId, $userId, $body, now()]);
            }
            $_SESSION['open_comments'] = $postId;
            break;

        case 'delete_comment':
            $comment = dbOne('SELECT user_id FROM comments WHERE id = ? AND post_id = ?', [(int) ($_POST['comment_id'] ?? 0), $postId]);
            if ($comment && ($comment['user_id'] == $userId || isAdmin())) {
                dbRun('DELETE FROM comments WHERE id = ?', [(int) $_POST['comment_id']]);
            }
            $_SESSION['open_comments'] = $postId;
            break;
    }

    redirect($feedUrl . '#post-' . $postId);
}

// Eén extra post ophalen om te weten of er nog een volgende pagina is
$posts = getPosts('approved', POSTS_PER_PAGE + 1, ($pageNumber - 1) * POSTS_PER_PAGE);
$hasMore = count($posts) > POSTS_PER_PAGE;
$posts = array_slice($posts, 0, POSTS_PER_PAGE);
$comments = getComments(array_column($posts, 'id'));

$openComments = $_SESSION['open_comments'] ?? null;
unset($_SESSION['open_comments']);
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="max-w-2xl md:mx-auto pb-24">
    <div class="bg-card shadow-md mx-7 mt-20 rounded-2xl text-text px-3 py-4 flex justify-between items-center">
        <h1 class="text-4xl">Foto's</h1>
        <?php if (isLoggedIn()): ?>
            <a href="?page=upload" class="bg-accent1 text-text px-4 py-1 rounded-lg">Foto plaatsen</a>
        <?php else: ?>
            <a href="?page=login" class="bg-accent1 text-text px-4 py-1 rounded-lg">Inloggen</a>
        <?php endif; ?>
    </div>

    <?php foreach ($posts as $index => $post): ?>
        <?php $postComments = $comments[$post['id']] ?? []; ?>
        <div class="bg-<?= cardColor($index) ?> shadow-md mx-7 mt-10 rounded-2xl text-text px-3 py-4 scroll-mt-20" id="post-<?= $post['id'] ?>">
            <div class="flex justify-between gap-2">
                <h2 class="text-xl"><?= e($post['title']) ?></h2>
                <div class="text-[12px] text-gray-200 shrink-0 text-right">
                    <?= formatDate($post['created_at']) ?>
                </div>
            </div>
            <p class="josefin-sans text-sm">door <?= e($post['username']) ?></p>

            <?php require VIEW_PATH . '/partials/post-images.view.php'; ?>

            <p class="josefin-sans mt-3"><?= nl2br(e($post['content'])) ?></p>

            <details class="mt-3" <?= $openComments == $post['id'] ? 'open' : '' ?>>
                <summary class="flex items-center list-none">
                    <form method="POST" action="<?= $feedUrl ?>" class="flex items-center">
                        <?= csrfField() ?>
                        <input type="hidden" name="action" value="like">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit" class="flex items-center rounded-full <?= $post['liked'] ? 'bg-accent-light' : '' ?> p-1"
                            aria-label="<?= $post['liked'] ? 'Niet meer leuk vinden' : 'Leuk vinden' ?>">
                            <img class="h-8 w-8" src="/icon/heart-icon.svg" alt="">
                        </button>
                        <span class="ms-1 me-4"><?= $post['like_count'] ?></span>
                    </form>
                    <span class="flex items-center cursor-pointer" aria-label="Reacties">
                        <img class="h-8 w-8" src="/icon/speech-bubble-black-icon.svg" alt="">
                        <span class="ms-2"><?= $post['comment_count'] ?></span>
                    </span>
                </summary>

                <div class="mt-3 flex flex-col gap-2">
                    <?php foreach ($postComments as $comment): ?>
                        <div class="bg-black/20 rounded-lg px-3 py-2">
                            <div class="flex justify-between gap-2 text-sm">
                                <span><?= e($comment['username']) ?></span>
                                <span class="text-[12px] text-gray-200"><?= formatDate($comment['created_at']) ?></span>
                            </div>
                            <p class="josefin-sans"><?= nl2br(e($comment['body'])) ?></p>
                            <?php if (isLoggedIn() && ($comment['user_id'] == currentUser()['id'] || isAdmin())): ?>
                                <form method="POST" action="<?= $feedUrl ?>" class="text-right" data-confirm="Reactie verwijderen?">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="action" value="delete_comment">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                    <button type="submit" class="text-[12px] underline">Verwijderen</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$postComments): ?>
                        <p class="josefin-sans text-sm">Nog geen reacties.</p>
                    <?php endif; ?>

                    <?php if (isLoggedIn()): ?>
                        <form method="POST" action="<?= $feedUrl ?>" class="flex gap-2 mt-1">
                            <?= csrfField() ?>
                            <input type="hidden" name="action" value="comment">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <input type="text" name="body" maxlength="500" required placeholder="Schrijf een reactie..." aria-label="Reactie"
                                class="w-full p-1 border-black rounded-lg bg-input text-text focus:ring-2">
                            <button type="submit" class="bg-background text-text px-3 py-1 rounded-lg">Plaats</button>
                        </form>
                    <?php else: ?>
                        <p class="josefin-sans text-sm"><a href="?page=login" class="underline">Log in</a> om te reageren.</p>
                    <?php endif; ?>
                </div>
            </details>
        </div>
    <?php endforeach; ?>

    <?php if (!$posts): ?>
        <div class="bg-card mx-7 mt-10 rounded-2xl text-text px-3 py-4">
            <p>Er zijn nog geen foto's geplaatst.</p>
        </div>
    <?php endif; ?>

    <div class="flex justify-between mx-7 mt-10">
        <?php if ($pageNumber > 1): ?>
            <a href="?page=feed&p=<?= $pageNumber - 1 ?>" class="bg-card text-text px-4 py-1 rounded-lg">Nieuwere foto's</a>
        <?php endif; ?>
        <?php if ($hasMore): ?>
            <a href="?page=feed&p=<?= $pageNumber + 1 ?>" class="bg-card text-text px-4 py-1 rounded-lg ms-auto">Oudere foto's</a>
        <?php endif; ?>
    </div>
</div>

<?php if (isLoggedIn()): ?>
    <a href="?page=upload" class="fixed bottom-5 right-5 h-14 w-14" aria-label="Foto plaatsen">
        <img class="h-14 w-14 rounded-full bg-white border-white border-1 shadow-md" src="/icon/plus-round-icon.svg" alt="">
    </a>
<?php endif; ?>
<?php require VIEW_PATH . '/footer.view.php'; ?>
