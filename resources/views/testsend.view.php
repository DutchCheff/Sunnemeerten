

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "my_content_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST["title"]);
    $content = $conn->real_escape_string($_POST["content"]);

    // Initialize an array to store image URLs
    $imageUrls = [];

    // Handle image uploads
    if (!empty($_FILES["images"]["name"][0])) {
        foreach ($_FILES["images"]["tmp_name"] as $index => $tmpName) {
            $filename = basename($_FILES["images"]["name"][$index]);
            $filetype = mime_content_type($tmpName); // Get the MIME type of the file
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($filetype, $allowed_types)) {
                $targetPath = "uploads/" . time() . "_" . $filename;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $path = $conn->real_escape_string($targetPath);
                    $imageUrls[] = $path; // Add the image URL to the array
                } else {
                    echo "<p style='color: red;'>Failed to move file: $filename</p>";
                }
            } else {
                echo "<p style='color: red;'>Invalid file type for image: $filename. Only JPEG, PNG, and GIF are allowed.</p>";
            }
        }
    }

    // Concatenate image URLs into a single string (comma-separated)
    $imageUrlsString = implode(',', $imageUrls);

    // Use prepared statement for post insertion
    $stmt = $conn->prepare("INSERT INTO posts (title, content, images) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imageUrlsString);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Post uploaded successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error uploading post: " . $stmt->error . "</p>";
    }
}
?>



<?php require_once '../resources/views/header.view.php'; ?>

<div class="bg-card mx-7 mt-28 rounded-2xl text-text px-3 py-4 mb-5">
    <h2 class="text-4xl font-semibold text-center mb-4">test send</h2>
    <a href="?page=testget" class="bg-accent2">test get</a>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block">Title</label>
            <input type="text" name="title" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label>Upload Images:</label>
            <input type="file" name="images[]" multiple accept="image/*">
        </div>

        <div class="mb-6">
            <label class="block">text</label>
            <textarea name="content" class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500" id="text"></textarea>
        </div>

        <div class="flex justify-between">
            <button type="button" class="bg-accent2 text-text px-4 py-2 rounded-lg hover:bg-gray-400">Voorwaarden</button>
            <button type="submit" formaction="?page=testsend" class="bg-accent1 text-text px-4 py-2 rounded-lg hover:bg-blue-600">Sign Up</button>
        </div>
    </form>
</div>
<?php require_once '../resources/views/footer.view.php'; ?>