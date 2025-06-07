<?php
session_start();

$config = require_once '../config/var/dp.php';

$error = '';
$success = '';

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4", $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if (empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
            $error = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'adresse email n'est pas valide.";
        } elseif ($password !== $confirmPassword) {
            $error = "Les mots de passe ne correspondent pas.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $error = "Ce nom d'utilisateur est déjà pris.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
                    $stmt->execute([$email, $username, $hashedPassword]);
                    $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                }
            }
        }
    }
} catch (PDOException $e) {
    $error = "Erreur de connexion à la base de données.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription</title>
    <link rel="stylesheet" href="/src/style/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/src/template/template.php"); ?>

    <div class="container" style="max-width: 500px;">
        <h2>Créer un compte</h2>

        <?php if ($error): ?>
            <div class="account-message error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="account-message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" class="toggle-form">
            <label for="email">Adresse email</label>
            <input type="email" name="email" id="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="btn-download">S'inscrire</button>
        </form>

        <p style="text-align: center; margin-top: 1rem;">
            Déjà un compte ? <a href="/src/account/signin.php">Connectez-vous ici</a>
        </p>
    </div>
</body>
</html>
