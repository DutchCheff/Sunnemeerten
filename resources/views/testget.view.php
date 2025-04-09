<?php
$thebg = 0;
$bg = 'card';
// Connect to the database
$conn = new mysqli("localhost", "root", "", "my_content_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all posts
$result = $conn->query("SELECT title, content, created_at, images FROM posts ORDER BY created_at DESC");
?>

<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4">
    <a href="?page=testsend" class="bg-accent2">test send</a>
</div>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-<?php echo $bg;?> mx-7 mt-10 rounded-2xl text-text px-3 py-4">
        <?php if ($thebg == 0) {$thebg = 1; $bg = 'accent1';} elseif ($thebg == 1) {$thebg = 2; $bg = 'accent2';} elseif ($thebg == 2) {$thebg = 0; $bg = 'card';}?>
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
                <div class="date"><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<?php require_once '../resources/views/footer.view.php'; ?>