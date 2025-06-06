<?php
session_start();

$error = '';
$success = '';

try {
    $config = require __DIR__ . '/../config/var/dp.php';
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error = "Erreur de connexion à la base de données.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (strlen($username) < 3 || strlen($username) > 32 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Le pseudo doit faire entre 3 et 32 caractères et ne contenir que des lettres, chiffres ou '_'.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Ce pseudo est déjà utilisé.";
        } else {
            
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $hash])) {
                    $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                } else {
                    $error = "Erreur lors de la création du compte.";
                }
            }
        }
    }
    usleep(500000); 
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créer un compte</title>
  <link rel="stylesheet" href="/src/style/style.css" />
</head>
<body>

  <?php include($_SERVER['DOCUMENT_ROOT'] . "/src/template/template.php"); ?>

  <div class="container" style="max-width:400px;margin-top:100px;">
        <h2>Créer un compte</h2>
        <?php if ($error): ?>
            <div style="color:#ff4d4f;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div style="color:#00fcd3;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label for="username">Pseudo</label><br>
            <input type="text" id="username" name="username" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"><br>

            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br>
            
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;"><br>
            
            <label for="confirm">Confirmer le mot de passe</label><br>
            <input type="password" id="confirm" name="confirm" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;"><br>
            
            <button type="submit" class="btn-download" style="width:100%;">Créer un compte</button>
        </form>
        <div style="text-align:center;margin-top:18px;">
            <a href="/src/account/signin.php" style="color:#00fcd3;">Déjà inscrit ? Se connecter</a>
        </div>
    </div>
</body>
</html>