<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<p class="text-muted">History Transaksi Pembelian</p>

<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>ID Pembelian</th>
            <th>Pembeli</th>
            <th>Waktu Pembelian</th>
            <th>Total Bayar</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $no => $item) : ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td><?= $item['id'] ?></td>
                <td><?= $item['username'] ?></td>
                <td><?= $item['created_at'] ?></td>
                <td>IDR <?= number_format($item['total_harga'], 0, ',', '.') ?></td>
                <td><?= $item['alamat'] ?></td>
                <td>
                    <?php if ($item['status'] == 1) : ?>
                        <span class="badge bg-success">Sudah Selesai</span>
                    <?php else : ?>
                        <span class="badge bg-warning text-dark">Belum Selesai</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-info btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetail"
                        data-id="<?= $item['id'] ?>">
                        Detail
                    </button>
                    <a href="pembelian/ubah-status/<?= $item['id'] ?>"
                       class="btn btn-<?= $item['status'] == 1 ? 'secondary' : 'primary' ?> btn-sm"
                       onclick="return confirm('Yakin ingin mengubah status pesanan ini?')">
                        Ubah Status
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalDetail = document.getElementById('modalDetail');
    modalDetail.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var body = document.getElementById('modalDetailBody');

        // Update judul modal
        document.getElementById('modalDetailLabel').textContent = 'Detail Transaksi #' + id;

        // Reset body
        body.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

        // Fetch data detail
        fetch('<?= base_url('pembelian/detail/') ?>' + id)
            .then(response => response.json())
            .then(function (data) {
                if (!data || data.length === 0) {
                    body.innerHTML = '<p class="text-muted">Tidak ada detail transaksi.</p>';
                    return;
                }

                var html = '';
                var ongkir = 0;

                data.forEach(function (item, index) {
                    var hargaAsli = item.harga_asli ? parseFloat(item.harga_asli) : 0;
                    var diskon = item.diskon ? parseFloat(item.diskon) : 0;
                    var hargaSetelahDiskon = Math.max(0, hargaAsli - diskon);
                    var subtotal = parseFloat(item.subtotal_harga);

                    html += '<div class="d-flex align-items-start mb-3">';
                    html += '<span class="me-3 fw-bold">' + (index + 1) + ')</span>';
                    if (item.foto) {
                        html += '<img src="<?= base_url('img/') ?>' + item.foto + '" width="60" class="me-3">';
                    }
                    html += '<div>';
                    html += '<p class="mb-0 fw-bold">' + item.nama + ' (IDR ' + hargaAsli.toLocaleString('id-ID') + ')</p>';
                    html += '<p class="mb-0 text-muted">(' + item.jumlah + ' pcs)</p>';
                    html += '<p class="mb-0">IDR ' + subtotal.toLocaleString('id-ID') + '</p>';
                    html += '</div></div>';
                });

                body.innerHTML = html;
            })
            .catch(function () {
                body.innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
            });
    });
});
</script>

<?= $this->endSection() ?>
