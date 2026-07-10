<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
// Ambil data diskon hari ini untuk header (digunakan oleh layout/header)
// sudah di-handle oleh DiscountController::index() lewat $discount yang di-pass
?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Tombol Tambah Data -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    Tambah Data
</button>

<!-- Tabel Data Diskon -->
<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($discounts as $no => $item) : ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td><?= date('Y-m-d', strtotime($item['tanggal'])) ?></td>
                <td><?= number_format($item['nominal'], 0, ',', '.') ?></td>
                <td>
                    <!-- Tombol Ubah -->
                    <button type="button" class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEdit"
                        data-id="<?= $item['id'] ?>"
                        data-tanggal="<?= $item['tanggal'] ?>"
                        data-nominal="<?= $item['nominal'] ?>">
                        Ubah
                    </button>
                    <!-- Tombol Hapus -->
                    <a href="diskon/delete/<?= $item['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- ======= Modal Tambah Data ======= -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('diskon') ?>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="tambahTanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tambahTanggal" name="tanggal"
                        value="<?= isset($oldInput['tanggal']) ? $oldInput['tanggal'] : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tambahNominal" class="form-label">Nominal</label>
                    <input type="number" class="form-control" id="tambahNominal" name="nominal"
                        value="<?= isset($oldInput['nominal']) ? $oldInput['nominal'] : '' ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<!-- End Modal Tambah -->

<!-- ======= Modal Edit Data ======= -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editTanggal" class="form-label">Tanggal</label>
                        <!-- Tanggal readonly, tidak bisa diubah -->
                        <input type="text" class="form-control bg-light" id="editTanggal" name="tanggal_display" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editNominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="editNominal" name="nominal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Edit -->

<script>
// Isi data modal Edit saat tombol Ubah diklik
document.addEventListener('DOMContentLoaded', function () {
    var modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        var button   = event.relatedTarget;
        var id       = button.getAttribute('data-id');
        var tanggal  = button.getAttribute('data-tanggal');
        var nominal  = button.getAttribute('data-nominal');

        // Format tanggal untuk tampilan readonly
        var tgl = new Date(tanggal);
        var formattedDate = (tgl.getDate().toString().padStart(2, '0')) + '/' +
                            ((tgl.getMonth()+1).toString().padStart(2, '0')) + '/' +
                            tgl.getFullYear();

        document.getElementById('editTanggal').value  = formattedDate;
        document.getElementById('editNominal').value  = nominal;

        // Set action form ke diskon/edit/{id}
        document.getElementById('formEdit').action = 'diskon/edit/' + id;
    });

    <?php if (!empty($oldInput)) : ?>
    // Buka modal tambah jika ada error validasi tambah
    var myModal = new bootstrap.Modal(document.getElementById('modalTambah'));
    myModal.show();
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
