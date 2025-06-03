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

  <div class="container">
    <h1>Pilotes Graphiques – Mettez à jour votre GPU</h1>
    <p>
      Les pilotes graphiques permettent à votre carte vidéo de fonctionner de manière optimale avec votre système. Que vous jouiez, fassiez du montage ou utilisiez des outils 3D, il est essentiel d’avoir les bons pilotes.
    </p>

    <section id="nvidia">
      <h2>
    <img src="/asset/logo/NVIDIA.png" alt="NVIDIA" style="height:1.5em;vertical-align:middle;margin-right:8px;">
    NVIDIA
  </h2>
      <p>
        Les cartes graphiques NVIDIA nécessitent des pilotes à jour pour tirer parti des dernières optimisations en jeu ou en création. Deux types de pilotes sont disponibles :
      </p>
      <ul>
        <li><strong>Game Ready Drivers (GRD)</strong> : optimisés pour les derniers jeux AAA.</li>
        <li><strong>Studio Drivers (SD)</strong> : pensés pour les logiciels de création (Adobe, Blender, etc.).</li>
      </ul>
      <p>
        Téléchargement : 
        <a class="driver-link" href="https://www.nvidia.com/Download/index.aspx" target="_blank">Site officiel NVIDIA</a>
      </p>
    </section>

    <section id="amd">
      <h2>
    <img src="/asset/logo/AMD.png" alt="AMD" style="height:1.5em;vertical-align:middle;margin-right:8px;">
    AMD
  </h2>
      <p>
        Les pilotes AMD permettent de profiter pleinement des performances des GPU Radeon. Le pack principal est :
      </p>
      <ul>
        <li><strong>Adrenalin Edition</strong> : améliore les performances de jeu, offre des outils de streaming, et permet la capture vidéo.</li>
      </ul>
      <p>
        Téléchargement :
        <a class="driver-link" href="https://www.amd.com/fr/support" target="_blank">Site officiel AMD</a>
      </p>
    </section>

    <section id="intel">
      <h2>
    <img src="/asset/logo/INTEL.png" alt="INTEL" style="height:1.5em;vertical-align:middle;margin-right:8px;">
    INTEL
  </h2>
      <p>
        Intel fournit des pilotes pour ses GPU intégrés (Intel UHD, Iris Xe) et dédiés (Intel Arc). Ces pilotes garantissent stabilité, compatibilité et mises à jour régulières.
      </p>
      <p>
        Téléchargement :
        <a class="driver-link" href="https://www.intel.fr/content/www/fr/fr/download-center/home.html" target="_blank">Centre de téléchargement Intel</a>
      </p>
    </section>
  </div>

</body>
</html>
