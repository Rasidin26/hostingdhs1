<table>
    <tr>
        <td colspan="4"><strong>PEMASUKKAN BULAN INI</strong></td>
    </tr>
    <tr>
        <td>Kategori</td>
        <td>Online</td>
        <td>Offline</td>
        <td>Total</td>
    </tr>
    <tr>
        <td>Voucher</td>
        <td>{{ $voucherOnlineMonth ?? 0 }}</td>
        <td>{{ $voucherOfflineMonth ?? 0 }}</td>
        <td>{{ $voucherMonth ?? 0 }}</td>
    </tr>
    <tr>
        <td>Billing</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Total</td>
        <td></td>
        <td></td>
        <td>${{ $totalPemasukkan ?? 0 }}</td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td colspan="2"><strong>PENGELUARAN BULAN INI</strong></td>
    </tr>
    <tr>
        <td>Kategori</td>
        <td>Jumlah</td>
    </tr>
    <tr>
        <td>Gaji Karyawan</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Pasang Baru</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Perbaikan Alat</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Bandwidth</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Bayar Kang Tagih</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Listrik/PDAM/Pulsa</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Marketing</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Lain-lain</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Total Pengeluaran</td>
        <td>{{ $totalPengeluaran ?? 0 }}</td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td colspan="2"><strong>RINGKASAN</strong></td>
    </tr>
    <tr>
        <td>Total Pemasukkan Bulan Ini</td>
        <td>{{ $totalPemasukkan ?? 0 }}</td>
    </tr>
    <tr>
        <td>Total Pengeluaran Bulan Ini</td>
        <td>{{ $totalPengeluaran ?? 0 }}</td>
    </tr>
    <tr>
        <td>Laba/Rugi Bulan Ini</td>
        <td>{{ $labaRugi ?? 0 }}</td>
    </tr>
    <tr>
        <td>Total Saldo Akumulasi</td>
        <td>{{ $saldoAkumulasi ?? 0 }}</td>
    </tr>
</table>
