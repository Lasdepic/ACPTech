<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /src/account/signin.php');
    exit;
}

$error = '';
$success = '';
$showChangePassword = false;
$showDeleteAccount = false;

// Gestion de l'affichage des formulaires après POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['show_change_password'])) {
        $showChangePassword = true;
    }
    if (isset($_POST['show_delete_account'])) {
        $showDeleteAccount = true;
    }
}

try {
    $config = require __DIR__ . '/../config/var/dp.php';
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupère les infos de l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: /src/account/signin.php');
        exit;
    }

    // Changement de mot de passe
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
        $showChangePassword = true;
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($current, $row['password'])) {
            $error = "Mot de passe actuel incorrect.";
        } elseif (strlen($new) < 8) {
            $error = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        } elseif (!preg_match('/[A-Z]/', $new) || !preg_match('/[a-z]/', $new) || !preg_match('/[0-9]/', $new)) {
            $error = "Le nouveau mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.";
        } elseif ($new !== $confirm) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $user['id']]);
            $success = "Mot de passe modifié avec succès.";
            $showChangePassword = false;
        }
    }

    // Suppression du compte
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
        $showDeleteAccount = true;
        $confirmDelete = $_POST['confirm_delete'] ?? '';
        if ($confirmDelete === 'SUPPRIMER') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            session_destroy();
            header('Location: /index.php');
            exit;
        } else {
            $error = "Veuillez écrire SUPPRIMER pour confirmer la suppression.";
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
    <title>Mon compte</title>
    <link rel="stylesheet" href="/src/style/style.css" />
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/src/template/template.php"); ?>

    <div class="container" style="max-width:500px;">
        <h2>Mon compte</h2>
        <?php if ($error): ?>
            <div class="account-message error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="account-message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="account-info">
            <strong>Pseudo :</strong> <?= htmlspecialchars($user['username']) ?><br>
            <strong>Email :</strong> <?= htmlspecialchars($user['email']) ?>
        </div>

        <div class="toggle-section">
            <form method="post" class="toggle-form-btn">
                <button type="submit" name="show_change_password" class="toggle-btn">Changer le mot de passe</button>
            </form>
            <?php if ($showChangePassword): ?>
            <form method="post" class="toggle-form">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" required>

                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit" name="change_password" class="btn-download">Valider le changement</button>
            </form>
            <?php endif; ?>
        </div>

        <div class="toggle-section">
            <form method="post" class="toggle-form-btn">
                <button type="submit" name="show_delete_account" class="toggle-btn danger-btn">Supprimer mon compte</button>
            </form>
            <?php if ($showDeleteAccount): ?>
            <form method="post" class="toggle-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                <p class="danger-text">Attention : cette action est définitive.<br>
                Pour confirmer, tapez <strong>SUPPRIMER</strong> ci-dessous.</p>
                <input type="text" name="confirm_delete" placeholder="Tapez SUPPRIMER" required>
                <button type="submit" name="delete_account" class="btn-download danger-btn">Supprimer mon compte</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>