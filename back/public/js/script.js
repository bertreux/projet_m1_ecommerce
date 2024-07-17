$(document).ready( function () {
    $('#myTable').DataTable({
        "oLanguage": {
            "sEmptyTable": "Aucun résultat"
        },
        "columnDefs": [
            { "orderable": false, "targets": [0, -1] }
        ],
        "order": [[1, 'asc']]
    });
} );