<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-12"> <!-- Form widened -->
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
                        <?php $rows = ['A','B','C','D','E1','E2','E3','E4','E5','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X']; ?>
                        <div id="placeRanges">
                            <div class="row range-block">
                                <div class="col mb-3">
                                    <label class="form-label">Rangée de début <span class="text-danger">*</span></label>
                                    <select class="form-control bg-dark text-light border-secondary" name="rowStart[]" required>
                                        <option value="">Sélectionner</option>
                                        <?php foreach($rows as $r): ?>
                                            <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner la rangée de début.</div>
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">N° place début <span class="text-danger">*</span></label>
                                     <input type="number" class="form-control bg-dark text-light border-secondary" name="minPlace[]" required>
                                     <div class="invalid-feedback">Veuillez entrer le numéro de place de début.</div>
                                 </div>
                                 <div class="col mb-3">
                                     <label class="form-label">Rangée de fin <span class="text-danger">*</span></label>
                                     <select class="form-control bg-dark text-light border-secondary" name="rowEnd[]" required>
                                        <option value="">Sélectionner</option>
                                        <?php foreach($rows as $r): ?>
                                            <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner la rangée de fin.</div>
                                </div>
                                <div class="col mb-3">
                                     <label class="form-label">N° place fin <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control bg-dark text-light border-secondary" name="maxPlace[]" required>
                                    <div class="invalid-feedback">Veuillez entrer le numéro de place de fin.</div>
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
document.addEventListener('DOMContentLoaded', function() {
    const addRangeBtn = document.getElementById('addRangeBtn');
    const placeRanges = document.getElementById('placeRanges');
    if (addRangeBtn && placeRanges) {
        addRangeBtn.addEventListener('click', function() {
            // On récupère le premier bloc existant comme modèle
            const firstBlock = placeRanges.querySelector('.range-block');
            if (firstBlock) {
                const newBlock = firstBlock.cloneNode(true);
                // On vide les champs du nouveau bloc
                newBlock.querySelectorAll('input, select').forEach(input => {
                    if (input.tagName.toLowerCase() === 'input') {
                        input.value = '';
                    } else if (input.tagName.toLowerCase() === 'select') {
                        input.selectedIndex = 0;
                    }
                });
                placeRanges.appendChild(newBlock);
            }
        });
    }
});
</script>