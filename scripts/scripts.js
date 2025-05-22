document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.reserve-place-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var row = this.getAttribute('data-row');
            var place = this.getAttribute('data-place');
            var code = row + place;
            document.getElementById('modalplaceCode').textContent = code;
            document.getElementById('reserveNumPlace').value = code;
            document.getElementById('nom').value = '';
            document.getElementById('prenom').value = '';
            var modal = new bootstrap.Modal(document.getElementById('reserveModal'));
            modal.show();
        });
    });

    var resSearch = document.getElementById('reservation-search');
    if (resSearch) {
        resSearch.addEventListener('input', function () {
            var filter = this.value.toLowerCase();
            document.querySelectorAll('#reservationsTable tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    }


    var evtSearch = document.getElementById('event-search');
    if (evtSearch) {
        evtSearch.addEventListener('input', function () {
            var filter = this.value.toLowerCase();
            document.querySelectorAll('.col-auto').forEach(function (col) {
                col.style.display = col.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    }

    document.querySelectorAll('.reserve-place-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var row = this.getAttribute('data-row');
            var place = this.getAttribute('data-place');
            var code = row + place;
            document.getElementById('modalplaceCode').textContent = code;
            document.getElementById('reserveNumPlace').value = code;
            document.getElementById('nom').value = '';
            document.getElementById('prenom').value = '';
            var modalEl = document.getElementById('reserveModal');
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    });



    document.addEventListener('DOMContentLoaded', () => {
        const rows = window.rows || []; 
        const placeRanges = document.getElementById('placeRanges');
        const addRangeBtn = document.getElementById('addRangeBtn');

        const updateEndOptions = select => {
            const endSelect = select.closest('.range-block').querySelector('select[name="rowEnd[]"]');
            endSelect.innerHTML = '<option value="">Sélectionner</option>';
            const idx = rows.indexOf(select.value);
            if (idx >= 0) rows.slice(idx).forEach(r => endSelect.add(new Option(r, r)));
        };

        placeRanges.addEventListener('change', e => {
            if (e.target.name === 'rowStart[]') updateEndOptions(e.target);
        });

        addRangeBtn.addEventListener('click', () => {
            const first = placeRanges.querySelector('.range-block');
            const newBlk = first.cloneNode(true);
            newBlk.querySelectorAll('input, select').forEach(el => el.value = '');
            newBlk.querySelector('select[name="rowEnd[]"]').innerHTML = '<option value="">Sélectionner</option>';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-outline-danger removeRangeBtn mb-3';
            removeBtn.textContent = 'Retirer cette fourchette';
            newBlk.append(removeBtn);
            placeRanges.append(newBlk);
        });
        placeRanges.addEventListener('click', e => {
            if (e.target.classList.contains('removeRangeBtn')) {
                e.target.closest('.range-block').remove();
            }
        });
    });
});