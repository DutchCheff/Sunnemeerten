<?php require_once '../public/database/db.php'; ?>
<?php
$thebg = 0;
$bg = 'card';

// Fetch all posts
$result = $conn->query("SELECT id, title, content, is_allowed, created_at, images FROM userposts ORDER BY created_at DESC");

?>

<?php require_once '../resources/views/header.view.php'; ?>
<script src="js/allowuserpost.js" defer></script>


<div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4">
    <a href="?page=feed" class="bg-accent2">feed</a>
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

                <div class="flex justify-between">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <div class="text-[12px] text-gray-200">
                    <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?>
                </div>
                
                </div>
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
                <div class="flex justify-evenly items-center my-4 h-10">
                    <img src="icon/check.svg" alt="Allow" class="h-6 w-6 object-contain cursor-pointer" onclick="updatePermission(<?= $row['id'] ?>, 1)">
                    <img src="icon/cross.svg" alt="Disallow" class="h-6 w-6 object-contain cursor-pointer" onclick="updatePermission(<?= $row['id'] ?>, 0)">
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="bg-card mx-7 mt-10 rounded-2xl text-text px-3 py-4">
        <p>No posts found.</p>
    </div>

<?php endif; ?>

<?php require_once '../resources/views/footer.view.php'; ?>