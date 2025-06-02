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





    
});