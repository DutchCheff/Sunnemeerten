<?php

const POSTS_PER_PAGE = 10;

// Haalt posts op met auteur, aantal likes/reacties en de afbeeldingen
function getPosts(?string $status, int $limit = POSTS_PER_PAGE, int $offset = 0, ?int $userId = null, bool $featuredOnly = false): array
{
    $where = [];
    $params = [currentUser()['id'] ?? 0];

    if ($status !== null) {
        $where[] = 'p.status = ?';
        $params[] = $status;
    }
    if ($userId !== null) {
        $where[] = 'p.user_id = ?';
        $params[] = $userId;
    }
    if ($featuredOnly) {
        $where[] = 'p.is_featured = 1';
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $posts = dbAll(
        "SELECT p.*, u.username,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
            (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id AND l.user_id = ?) AS liked
        FROM posts p
        JOIN users u ON u.id = p.user_id
        $whereSql
        ORDER BY p.created_at DESC, p.id DESC
        LIMIT $limit OFFSET $offset",
        $params
    );

    return attachImages($posts);
}

function countPosts(string $status): int
{
    return (int) dbOne('SELECT COUNT(*) AS total FROM posts WHERE status = ?', [$status])['total'];
}

function attachImages(array $posts): array
{
    if (!$posts) {
        return [];
    }

    $ids = array_column($posts, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $images = dbAll("SELECT post_id, path FROM post_images WHERE post_id IN ($placeholders) ORDER BY sort_order", $ids);

    foreach ($posts as &$post) {
        $post['images'] = array_column(array_filter($images, fn ($image) => $image['post_id'] == $post['id']), 'path');
    }

    return $posts;
}

function getComments(array $postIds): array
{
    if (!$postIds) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($postIds), '?'));
    $comments = dbAll(
        "SELECT c.*, u.username FROM comments c
        JOIN users u ON u.id = c.user_id
        WHERE c.post_id IN ($placeholders)
        ORDER BY c.created_at",
        $postIds
    );

    $grouped = [];
    foreach ($comments as $comment) {
        $grouped[$comment['post_id']][] = $comment;
    }

    return $grouped;
}

function deletePost(int $postId): void
{
    foreach (dbAll('SELECT path FROM post_images WHERE post_id = ?', [$postId]) as $image) {
        deleteImage($image['path']);
    }
    dbRun('DELETE FROM posts WHERE id = ?', [$postId]);
}

function deleteUser(int $userId): void
{
    foreach (dbAll('SELECT id FROM posts WHERE user_id = ?', [$userId]) as $post) {
        deletePost($post['id']);
    }
    dbRun('DELETE FROM volunteers WHERE user_id = ?', [$userId]);
    dbRun('DELETE FROM users WHERE id = ?', [$userId]);
}

function statusLabel(string $status): string
{
    return ['pending' => 'Wacht op goedkeuring', 'approved' => 'Goedgekeurd', 'rejected' => 'Afgekeurd'][$status] ?? $status;
}
