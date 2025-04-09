<?php require_once '../public/database/db.php'; ?>
<?php
$thebg = 0;
$bg = 'card';
// Fetch all posts
$result = $conn->query("SELECT title, content, is_allowed, created_at, images FROM userposts WHERE is_allowed = 1 ORDER BY created_at DESC");
?>

<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card mx-7 mt-20 rounded-2xl text-text px-3 py-4">
    <a href="?page=admin" class="bg-accent1">admin</a>
</div>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-<?php echo $bg; ?> mx-7 mt-10 rounded-2xl text-text px-3 py-4">
            <?php if ($thebg == 0) {
                $thebg = 1;
                $bg = 'accent1';
            } elseif ($thebg == 1) {
                $thebg = 2;
                $bg = 'accent2';
            } elseif ($thebg == 2) {
                $thebg = 0;
                $bg = 'card';
            } ?>
            <div class="post">

                <h2><?php echo htmlspecialchars($row['title']); ?></h2>

                <!-- Display images -->
                <?php if (!empty($row['images'])): ?>
                    <div class="post-images">
                        <?php
                        // Split the image URLs into an array
                        $images = explode(',', $row['images']);
                        foreach ($images as $image):
                            $imagePath = trim($image); // Remove any extra whitespace
                            if (file_exists($imagePath)): // Check if the image file exists
                                echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Post Image" class="post-image" style="max-width: 100%; height: auto; margin-top: 10px;">';
                            endif;
                        endforeach;
                        ?>
                    </div>
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <div class="mt-3">
                    <button>
                        <img class="h-10 w-10 me-3" src="icon/speech-bubble-black-icon.svg" alt="">
                    </button>
                    <button>
                        <img class="h-10 w-10 me-3" src="icon/heart-icon.svg" alt="">
                    </button>
                    <div class="date">
                        <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="bg-card mx-7 mt-10 rounded-2xl text-text px-3 py-4">
        <p>No posts found.</p>
    </div>

<?php endif; ?>


<div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4 mb-5">
    <h2 class="mb-4">Template</h2>
    <img src="images/27.jpg" alt="">
    <p class="my-4">Faka broer Tatta tappen flous zemmer jnoen rocken ekkie abbie, kinky l8r friendzone akka keba toeter, asser apoe scapoe rus que pasa od purra.</p>
    <div>
        <button>
            <img class="h-10 w-10 me-3" src="icon/speech-bubble-black-icon.svg" alt="">
        </button>
        <button>
            <img class="h-10 w-10 me-3" src="icon/heart-icon.svg" alt="">
        </button>
    </div>
</div>
<a href="?page=usersend" class="fixed bottom-2 right-4 bg-accent1 rounded-xl h-15 w-15 m-2">
    <img class="h-10 w-10 m-1" src="icon/plus-round-icon.svg" alt="">
</a>
<?php require_once '../resources/views/footer.view.php'; ?>