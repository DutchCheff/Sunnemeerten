<?php require_once '../public/database/db.php'; ?>
<?php require_once '../resources/views/header.view.php'; ?>

<?php
session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $errors[] = "Vul alle velden in.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Voer een geldig e-mailadres in.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // Login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: ?page=landing'); // Adjust this to your actual route
                exit;
            } else {
                $errors[] = "Ongeldig wachtwoord.";
            }
        } else {
            $errors[] = "Gebruiker niet gevonden.";
        }
        $stmt->close();
    }
}
?>

<div class="bg-inherit">
    <div class="mt-16 mb-10">
        <img src="/images/logo1.png" alt="Logo" class="scale-90">
    </div>
    <div class="bg-card shadow-md mx-7 mb-4 rounded-2xl text-text px-6 py-4">
        <div>
            <h1 class="text-4xl text-center my-4">Login</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4 mt-3">
                <label>Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">

            </div>

            <div class="mb-4">
                <label class="block">Wachtwoord</label>
                <input type="password" name="password" required
                       class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>

            <div>
                <button type="submit"
                        class="bg-accent1 text-text px-4 py-1 rounded-lg w-full mb-4 mt-4 text-base">
                    Inloggen
                </button>
            </div>

            <div class="flex gap-2 w-full">
                <div class="w-1/2">
                <a href="?page=Guest" class="bg-accent2 text-text px-4 py-1 rounded-lg w-full mb-4 mt-2 text-base text-center block">
                        Gast
                    </a>
                </div>
                <div class="w-1/2">
                    <a href="?page=register" class="bg-background text-text px-4 py-1 rounded-lg w-full mb-4 mt-2 text-base text-center block">
                        Registreren
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once '../resources/views/footer.view.php'; ?>
