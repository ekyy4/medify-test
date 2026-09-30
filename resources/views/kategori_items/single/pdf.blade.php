<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori Item {{ $data->kode }}</title>
    <style>
        @page {
            margin: 40px 40px 65px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h2 {
            margin: 0 0 24px;
            text-align: center;
        }

        .info {
            margin-bottom: 22px;
        }

        .info td {
            padding: 3px 8px 3px 0;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #777;
            padding: 7px;
            text-align: left;
        }

        .item-table th {
            background-color: #eee;
        }

        .item-table tr {
            page-break-inside: avoid;
        }

        .item-table thead {
            display: table-header-group;
        }

        .footer {
            position: fixed;
            bottom: -42px;
            left: 0;
            right: 0;
            border-top: 1px solid #aaa;
            padding-top: 8px;
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <h2>DATA KATEGORI ITEM</h2>

    <table class="info">
        <tr>
            <td>Nama Kategori</td>
            <td>:</td>
            <td>{{ $data->nama }}</td>
        </tr>
        <tr>
            <td>Kode Kategori</td>
            <td>:</td>
            <td>{{ $data->kode }}</td>
        </tr>
        <tr>
            <td>Jumlah Item</td>
            <td>:</td>
            <td>{{ $data->masterItems->count() }}</td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Jenis</th>
                <th>Harga Beli</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data->masterItems as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">
                        Belum ada Master Item pada kategori ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $printed_at }}
    </div>
</body>
</html>
