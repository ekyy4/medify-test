<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
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

        var dataTableObj = $('#table').DataTable();

        $.ajax({
            url: '{{ url("master-items/search") }}',
            dataType: 'json',
            data: {
                kode: $('#filter-kode').val(),
                nama: $('#filter-nama').val(),
                hargamin: $('#filter-harga-min').val(),
                hargamax: $('#filter-harga-max').val()
            },
            success: function(results) {
                dataTableObj.clear();

                $.each(results.data, function(index, item) {
                    var harga_beli = Number(item.harga_beli);
                    var laba = Number(item.laba);
                    var harga_jual = Math.round(
                        harga_beli + (harga_beli * laba / 100)
                    );

                    var html = `<a href="{{ url('master-items/view') }}/${encodeURIComponent(item.kode)}"
                                   class="btn btn-primary">View</a>`;

                    dataTableObj.row.add([
                        item.kode,
                        item.nama,
                        item.jenis,
                        harga_beli,
                        harga_jual,
                        item.supplier,
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
