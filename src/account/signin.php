<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Exemple de vérification (à remplacer par votre logique de vérification réelle)
    if ($email === 'admin@example.com' && $password === 'password123') {
        // Connexion réussie
        $_SESSION['user'] = $email;
        header('Location: /index.php');
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pilotes Graphiques</title>
  <link rel="stylesheet" href="/src/style/style.css" />
</head>
<body>

  <?php include($_SERVER['DOCUMENT_ROOT'] . "/src/template/template.php"); ?>

  <div class="container" style="max-width:400px;margin-top:100px;">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <div style="color:#ff4d4f;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;"><br>
            
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;"><br>
            
            <button type="submit" class="btn-download" style="width:100%;">Se connecter</button>
        </form>
        <div style="text-align:center;margin-top:18px;">
            <a href="/src/account/signup.php" style="color:#00fcd3;">Créer un compte</a>
        </div>
    </div>
</body>
</html>