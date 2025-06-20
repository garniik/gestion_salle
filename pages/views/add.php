<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-light border-secondary shadow-lg">
                <div class="card-header border-bottom ">
                    <h3 class="mb-0 text-center">Créer un nouvelle evenement</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= strpos($message, 'Erreur') === false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST" novalidate>
                        <!-- Nom evenement -->
                        <div class="mb-3">
                            <label for="nomEvent" class="form-label ">Nom de l'evenement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark text-light border-secondary" id="nomEvent" name="nomEvent" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un nom.
                            </div>
                        </div>
                        <!-- date evenement -->
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Date de l'evenement <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-dark text-light border-secondary" id="dateEvent" name="dateEvent" required>
                            <div class="invalid-feedback">
                                Veuillez entrer une date.
                            </div>
                        </div>
                        <!-- prix place -->
                        <div class="mb-3">
                            <label for="prixPlace" class="form-label">Prix de la place <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control bg-dark text-light border-secondary" id="prixPlace" name="prixPlace" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un prix.
                            </div>
                        </div>
                        <!-- fourchettes place disponible -->
                        <?php $rows = ['A','B','C','D','E1','E2','E3','E4','E5','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X']; ?>
                        <div id="placeRanges">
                            <div class="row range-block">
                                <div class="col mb-3">
                                    <label for="rowStart_0" class="form-label">Rangée de début <span class="text-danger">*</span></label>
                                    <select id="rowStart_0" class="form-control bg-dark text-light border-secondary" name="rowStart[]" required>
                                        <option value="">Sélectionner</option>
                                        <?php foreach($rows as $r): ?>
                                            <option value="<?= $r ?>"><?= $r ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner la rangée de début.</div>
                                </div>
                                <div class="col mb-3">
                                    <label for="minPlace_0" class="form-label">N° place début <span class="text-danger">*</span></label>
                                     <input id="minPlace_0" type="number" class="form-control bg-dark text-light border-secondary" name="minPlace[]" required>
                                     <div class="invalid-feedback">Veuillez entrer le numéro de place de début.</div>
                                 </div>
                                 <div class="col mb-3">
                                     <label for="rowEnd_0" class="form-label">Rangée de fin <span class="text-danger">*</span></label>
                                     <select id="rowEnd_0" class="form-control bg-dark text-light border-secondary" name="rowEnd[]" required>
                                        <option value="">Sélectionner</option>
                                        <?php foreach($rows as $r): ?>
                                            <option value="<?= $r ?>"><?= $r ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner la rangée de fin.</div>
                                </div>
                                <div class="col mb-3">
                                     <label for="maxPlace_0" class="form-label">N° place fin <span class="text-danger">*</span></label>
                                    <input id="maxPlace_0" type="number" class="form-control bg-dark text-light border-secondary" name="maxPlace[]" required>
                                    <div class="invalid-feedback">Veuillez entrer le numéro de place de fin.</div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-light mb-3" id="addRangeBtn">+ Ajouter une fourchette</button>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const rows = <?= json_encode($rows) ?>;
    const placeRanges = document.getElementById('placeRanges');
    const addRangeBtn = document.getElementById('addRangeBtn');

    // Fonction pour mettre à jour les options du menu de droite
    function updateEndOptions(select) {
        const block = select.closest('.range-block');
        const endSelect = block.querySelector('select[name="rowEnd[]"]');
        if (!endSelect) return;
        

        const currentValue = endSelect.value;
        
        endSelect.innerHTML = '<option value="">Sélectionner</option>';
        

        const selectedValue = select.value;
        if (selectedValue) {
            const startIndex = rows.indexOf(selectedValue);
            if (startIndex >= 0) {

                for (let i = startIndex; i < rows.length; i++) {
                    const option = document.createElement('option');
                    option.value = rows[i];
                    option.textContent = rows[i];
                    endSelect.appendChild(option);
                }
                

                if (currentValue) {
                    const remainingOptions = Array.from(endSelect.options).map(opt => opt.value);
                    if (remainingOptions.includes(currentValue)) {
                        endSelect.value = currentValue;
                    }
                }
            }
        }
    }

    function setupRangeBlock(block) {
        const startSelect = block.querySelector('select[name="rowStart[]"]');
        if (startSelect) {
            const newStartSelect = startSelect.cloneNode(true);
            startSelect.parentNode.replaceChild(newStartSelect, startSelect);
            
            newStartSelect.addEventListener('change', function() {
                updateEndOptions(this);
            });

            if (newStartSelect.value) {
                updateEndOptions(newStartSelect);
            }
        }
    }

    document.querySelectorAll('.range-block').forEach(setupRangeBlock);

    addRangeBtn.addEventListener('click', () => {
        const first = placeRanges.querySelector('.range-block');
        if (!first) return;
        
        const newBlk = first.cloneNode(true);

        newBlk.querySelectorAll('input, select').forEach(el => {
            el.value = '';
            if (el.name === 'rowEnd[]') {
                el.innerHTML = '<option value="">Sélectionner</option>';
            }
        });

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm mt-2';
        removeBtn.textContent = 'Supprimer cette fourchette';
        removeBtn.onclick = function() {
            const allBlocks = document.querySelectorAll('.range-block');
            if (allBlocks.length > 1) {
                newBlk.remove();
            } else {
                alert('Au moins une fourchette doit être définie');
            }
        };
        

        placeRanges.appendChild(newBlk);
        

        setupRangeBlock(newBlk);
        

        newBlk.querySelector('.col:last-child').appendChild(removeBtn);
    });
});
</script>
