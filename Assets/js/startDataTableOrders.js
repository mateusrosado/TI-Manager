var table = new DataTable('.dataTable', {
    info: false,
    ordering: true,
    paging: true,
    searching: true,
    responsive: true,
    bProcessing: true,
    deferRender: true,
    pageLength: 10,
    scrollX: true,
    sScrollX: "100%",
    sScrollXInner: "100%",
    aaSorting: [[0, "asc"]]
});

$('#filterProduct').on('keyup', function () {
    table.search(this.value).draw();
});