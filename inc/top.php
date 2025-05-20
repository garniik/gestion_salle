<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand fw-bold" href="index.php">

      Gestionnaire d'evenements
    </a>

    <!-- Bouton pour mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Contenu de la navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
        $list_menus = array(
          'add' => 'ajouter un evement',
        );

        foreach ($list_menus as $key => $menu): ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?element=pages&action=<?= urlencode($key); ?>"><?= htmlspecialchars($menu); ?></a>
          </li>
        <?php endforeach; ?>
        <?php if (!empty($_SESSION['authenticated'])): ?>
          <li class="nav-item">
            <a class="nav-link text-danger" href="index.php?action=logout">Déconnexion</a>
          </li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>
