<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /src/account/signin.php');
    exit;
}

$error = '';
$success = '';
$avatarPath = '';

try {
    $config = require __DIR__ . '/../config/var/dp.php';
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupère les infos de l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT id, username, email, avatar FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: /src/account/signin.php');
        exit;
    }

    $avatarPath = $user['avatar'] ?? '';

    // Gestion de l'upload d'avatar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 2 * 1024 * 1024; // 2 Mo

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = "Format de fichier non autorisé (jpg, jpeg, png, gif).";
        } elseif ($file['size'] > $maxSize) {
            $error = "Fichier trop volumineux (max 2 Mo).";
        } else {
            $uploadDir = __DIR__ . '/../uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'avatar_' . $user['id'] . '_' . time() . '.' . $ext;
            $dest = $uploadDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // Met à jour le chemin de l'avatar en base
                $avatarUrl = '/src/uploads/avatars/' . $filename;
                $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->execute([$avatarUrl, $user['id']]);
                $success = "Avatar mis à jour avec succès.";
                $avatarPath = $avatarUrl;
            } else {
                $error = "Erreur lors de l'upload du fichier.";
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
    <title>Mon compte</title>
    <link rel="stylesheet" href="/src/style/style.css" />
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/src/template/template.php"); ?>

    <div class="container" style="max-width:500px;margin-top:100px;">
        <h2>Mon compte</h2>
        <?php if ($error): ?>
            <div style="color:#ff4d4f;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div style="color:#00fcd3;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div style="text-align:center;margin-bottom:24px;">
            <?php if ($avatarPath): ?>
                <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #00fcd3;">
            <?php else: ?>
                <img src="/src/uploads/avatars/default.png" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #00fcd3;">
            <?php endif; ?>
        </div>

        <form method="post" enctype="multipart/form-data" style="text-align:center;">
            <label for="avatar">Changer d'avatar :</label><br>
            <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png,.gif" required><br>
            <button type="submit" class="btn-download" style="margin-top:12px;">Mettre à jour</button>
        </form>

        <div style="margin-top:32px;">
            <strong>Pseudo :</strong> <?= htmlspecialchars($user['username']) ?><br>
            <strong>Email :</strong> <?= htmlspecialchars($user['email']) ?><br>
        </div>
    </div>
</body>
</html>