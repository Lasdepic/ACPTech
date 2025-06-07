<?php
session_start();

$config = require_once '../config/var/dp.php';

$error = '';

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4", $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $identifier = trim($_POST['identifier'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            $error = "Veuillez entrer votre identifiant et votre mot de passe.";
        } else {
            // Chercher soit par email, soit par nom d'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :identifier OR email = :identifier");
            $stmt->execute(['identifier' => $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                header("Location: /index.php");
                exit;
            } else {
                $error = "Identifiants incorrects.";
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
    <title>Connexion</title>
    <link rel="stylesheet" href="../style/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/src/template/template.php"); ?>

    <div class="container" style="max-width:500px;">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <div class="account-message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="toggle-form">
    <label for="identifier">Adresse email ou nom d'utilisateur</label>
    <input type="text" name="identifier" id="identifier" required>

    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" required>

    <button type="submit" class="btn-download">Se connecter</button>
</form>

        <p style="text-align: center;">Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html>