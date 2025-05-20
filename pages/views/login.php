<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 mx-auto">
            <div class="card bg-dark text-light border-secondary shadow-lg">
                <div class="card-header border-bottom">
                    <h3 class="mb-0 text-center">Se connecter</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= strpos($message, 'Erreur') === false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST" novalidate>
                        <!-- Nom utilisateur -->
                        <div class="mb-3">
                            <label for="Username" class="form-label ">Nom d'utilisateur <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light border-secondary" id="Username" name="Username" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un nom d'utilisateur.
                            </div>
                        </div>
                        <!-- mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">mot de passe   <span class="text-danger">*</span></label>
                            <input type="password" class="form-control bg-dark text-light border-secondary" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Veuillez entrer le mot de passe.
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
