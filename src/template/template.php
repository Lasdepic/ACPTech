<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <link rel="stylesheet" href="/src/style/style.css">
    <meta name="google-site-verification" content="p0y4MBB087_b3qE61N0WEgWpKppbGeQUDSdsYuv1V-Q" />
    <title>Document</title>
</head>
<body>
    <div id="particles-js"></div>

    <header>
        <h1><a href="/index.php" style="color: inherit; text-decoration: none;">DPPTech</a></h1>
        <p>Divers pilotes pc</p>

        <nav class="nav-menu">
  <ul>
    <li class="dropdown">
      <a href="#">Driver</a>
      <ul class="dropdown-content">
        <li><a href="/src/pages/carteGraphique.php">Carte graphique</a></li>
        <li><a href="/src/pages/speak.php">Discussion</a></li>
        <li><a href="/src/pages/launcher.php">Launcher</a></li>
      </ul>
    </li>
    <li><a href="/src/pages/UserDiag.php">User Diag</a></li>
    <li><a href="/src/pages/monitoring.php">Monitoring</a></li>
    <li><a href="/src/pages/forum/forum.php">Forum</a></li>
    <li class="dropdown">
  <a href="/src/pages/retogaming.php">Retro Gaming</a>
  <ul class="dropdown-content">
    <li><a href="/src/pages/RetroGaming/retrobat.php">Retobat</a></li>
    <li><a href="/src/pages/RetroGaming/retroArch.php">RetroArche</a></li>
    <li><a href="/src/pages/RetroGaming/playstation.php">PlayStation</a></li>
    <li><a href="/src/pages/RetroGaming/xbox.php">Xbox</a></li>
    <li><a href="/src/pages/RetroGaming/nintendo.php">Nintendo</a></li>
    <li><a href="/src/pages/RetroGaming/sega.php">Sega</a></li>
    <li><a href="/src/pages/RetroGaming/mame.php">MAME</a></li>
  </ul>
</li>
  </ul>
   <div class="account-actions">
    <?php
      if (session_status() === PHP_SESSION_NONE) session_start();
      if (isset($_SESSION['user'])):
    ?>
      <a href="/src/config/myAccount.php">Mon compte</a>
      <a href="/src/account/signout.php">Déconnexion</a>
    <?php else: ?>
      <a href="/src/account/login.php">Connexion</a>
      <a href="/src/account/signup.php">Inscription</a>
    <?php endif; ?>
  </div>
</nav>
   </header>
<script src="/src/script/animation.js"></script>
</div>
</body>
</html>