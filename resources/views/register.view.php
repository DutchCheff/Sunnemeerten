<?php require_once '../public/database/db.php'; ?>
<?php require_once '../resources/views/header.view.php'; ?>
<?php
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Alle velden zijn verplicht.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Voer een geldig e-mailadres in.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Wachtwoorden komen niet overeen.";
    }

    // If no errors, proceed
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header('Location: ?page=login'); // Adjust path to your route/view
            exit;
        } else {
            $errors[] = "Er is iets misgegaan: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<div class="bg-card mx-7 mt-28 rounded-2xl text-text px-6 py-4">
    <h2 class="text-4xl font-semibold text-center mb-4">Registreren</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block">Naam</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label class="block">Wachtwoord</label>
            <input type="password" name="password" required
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label class="block">Herhaal wachtwoord</label>
            <input type="password" name="confirm_password" required
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-between">
            <button type="button" class="bg-accent2 text-text px-4 py-2 rounded-lg hover:bg-gray-400">Voorwaarden</button>
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg hover:bg-blue-600">Sign Up</button>
        </div>
    </form>
</div>

<?php require_once '../resources/views/footer.view.php'; ?>

