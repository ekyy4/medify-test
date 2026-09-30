<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#kategories-table').DataTable({
            searching: false,
            order: [[0, 'desc']]
        });

        $('.btn-get-data').click(function(event) {
            event.preventDefault();
            getData();
        });

        getData();
    });

    function getData() {
        $('#loading-filter').show();

        var dataTableObj = $('#kategories-table').DataTable();

        $.ajax({
            url: '{{ url("kategori-items/search") }}',
            dataType: 'json',
            data: {
                kode: $('#filter-kode').val(),
                nama: $('#filter-nama').val()
            },
            success: function(results) {
                dataTableObj.clear();

                $.each(results.data, function(index, item) {
                    var html = `<a href="{{ url('kategori-items/view') }}/${encodeURIComponent(item.kode)}"
                                   class="btn btn-primary btn-sm">View</a>`;

                    dataTableObj.row.add([
                        item.kode,
                        item.nama,
                        item.master_items_count,
                        html
                    ]);
                });

                dataTableObj.draw();
                $('#loading-filter').hide();
            },
            error: function() {
                alert('Terjadi kesalahan server, tidak dapat mengambil data');
                $('#loading-filter').hide();
            }
        });
    }
</script>
