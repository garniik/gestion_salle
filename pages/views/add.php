<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10"> <!-- Largeur ajustée pour correspondre au style du Maker -->
            <div class="card bg-dark text-light border-secondary shadow-lg">
                <div class="card-header border-bottom text-warning">
                    <h3 class="mb-0 text-center">Créer un nouvelle evenement</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= strpos($message, 'Erreur') === false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Nom principal -->
                        <div class="mb-3">
                            <label for="primaryName" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light border-secondary" id="primaryName" name="primaryName" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un nom.
                            </div>
                        </div>

                        <!-- Année de naissance -->
                        <div class="mb-3">
                            <label for="birthYear" class="form-label">Année de naissance</label>
                            <input type="number" class="form-control bg-dark text-light border-secondary" id="birthYear" name="birthYear" min="1800" max="<?= date('Y') ?>">
                        </div>

                        <!-- Année de décès -->
                        <div class="mb-3">
                            <label for="deathYear" class="form-label">Année de décès</label>
                            <input type="number" class="form-control bg-dark text-light border-secondary" id="deathYear" name="deathYear" min="1800" max="<?= date('Y') ?>">
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-warning">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation du formulaire côté client
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>