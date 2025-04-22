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
                        <!-- Nom evenement -->
                        <div class="mb-3">
                            <label for="eventName" class="form-label">Nom de l'evenement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light border-secondary" id="eventName" name="eventName" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un nom.
                            </div>
                        </div>

                        <!-- date evenement -->
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Date de l'evenement <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-dark text-light border-secondary" id="eventDate" name="eventDate" required>
                            <div class="invalid-feedback">
                                Veuillez entrer une date.
                            </div>
                        </div>

                        <!-- fourchettes N° place disponible -->
                        <div id="placeRanges">
                            <div class="row range-block">
                                <div class="col mb-3">
                                    <label for="minPlace_0" class="form-label">N° place minimum <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control bg-dark text-light border-secondary" id="minPlace_0" name="minPlace[]" required>
                                    <div class="invalid-feedback">Veuillez entrer un nombre.</div>
                                </div>
                                <div class="col mb-3">
                                    <label for="maxPlace_0" class="form-label">N° place maximum <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control bg-dark text-light border-secondary" id="maxPlace_0" name="maxPlace[]" required>
                                    <div class="invalid-feedback">Veuillez entrer un nombre.</div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-light mb-3" id="addRangeBtn">+ Ajouter une fourchette</button>

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

document.getElementById('addRangeBtn').addEventListener('click', () => {
    const container = document.getElementById('placeRanges');
    const idx = container.querySelectorAll('.range-block').length;
    const template = container.querySelector('.range-block');
    const clone = template.cloneNode(true);
    // update ids
    clone.querySelector('label[for^="minPlace"]').setAttribute('for', `minPlace_${idx}`);
    clone.querySelector('input[name="minPlace[]"]').setAttribute('id', `minPlace_${idx}`);
    clone.querySelector('label[for^="maxPlace"]').setAttribute('for', `maxPlace_${idx}`);
    clone.querySelector('input[name="maxPlace[]"]').setAttribute('id', `maxPlace_${idx}`);
    container.appendChild(clone);
});
</script>