<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight: bold; text-align: center;">
            <th style="border: 1px solid #000000; padding: 6px;">No</th>
            <th style="border: 1px solid #000000; padding: 6px;">Kode Barang</th>
            <th style="border: 1px solid #000000; padding: 6px;">Kategori</th>
            <th style="border: 1px solid #000000; padding: 6px;">Nama Barang / Peralatan</th>
            <th style="border: 1px solid #000000; padding: 6px;">Satuan</th>
            <th style="border: 1px solid #000000; padding: 6px;">Total Masuk</th>
            <th style="border: 1px solid #000000; padding: 6px;">Total Keluar</th>
            <th style="border: 1px solid #000000; padding: 6px;">Stok Akhir</th>
            <th style="border: 1px solid #000000; padding: 6px;">Kondisi / Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barang as $index => $item)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000000; text-align: center; mso-number-format:'\@';">{{ $item->kode_barang ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: left;">{{ $item->kategori->nama_kategori ?? 'Umum' }}</td>
                <td style="border: 1px solid #000000; text-align: left;">{{ $item->nama_barang }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->satuan }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->total_masuk }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->total_keluar }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->stok_sekarang }}</td>
                <td style="border: 1px solid #000000; text-align: center;">
                    @if ($item->stok_sekarang <= 0)
                        Habis
                    @elseif ($item->stok_sekarang <= ($item->stok_minimum ?? 5))
                        Menipis
                    @else
                        Tersedia
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
