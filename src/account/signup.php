<?php
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    // Validation simple
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        if ($email === 'admin@example.com') {
            $error = "Cet email est déjà utilisé.";
        } else {
            $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            // Vous pouvez ici enregistrer l'utilisateur dans la base de données
        }
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
        <h2>Créer un compte</h2>
        <?php if ($error): ?>
            <div style="color:#ff4d4f;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div style="color:#00fcd3;text-align:center;margin-bottom:16px;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required style="width:100%;padding:10px;margin:10px 0 20px 0;border-radius:6px;border:1px solid #00fcd3;background:#13161d;color:#d6dee6;"><br>
            
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



