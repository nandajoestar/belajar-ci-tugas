<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?= form_open('keranjang/order') ?>
<div class="row">
    <!-- Kolom Kiri: Form Data Pembeli -->
    <div class="col-md-5">
        <div class="mb-3">
            <label class="form-label fw-bold">Nama</label>
            <p><?= session()->get('username') ?></p>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label fw-bold">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required placeholder="Masukkan alamat pengiriman..."></textarea>
        </div>
        <div class="mb-3">
            <label for="kelurahan" class="form-label fw-bold">Kelurahan</label>
            <select class="form-select" id="kelurahan" name="kelurahan">
                <option value="PENDRIKAN KIDUL, SEMARANG TENGAH, SEMARANG, JAWA TENGAH">PENDRIKAN KIDUL, SEMARANG TENGAH, SEMARANG, JAWA TENGAH</option>
                <option value="SEMARANG UTARA, SEMARANG">SEMARANG UTARA, SEMARANG</option>
                <option value="BANYUMANIK, SEMARANG">BANYUMANIK, SEMARANG</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Layanan</label>
            <p>JNE City Courier (CTCYES) - estimasi 1 day</p>
            <input type="hidden" name="ongkir" value="11000">
            <p class="text-muted">Ongkir: IDR 11,000</p>
        </div>
        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
    </div>

    <!-- Kolom Kanan: Ringkasan Pesanan -->
    <div class="col-md-7">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalDiskonKeseluruhan = 0;
                foreach ($items as $item) :
                    $nominalDiskon      = !empty($discount) ? $discount['nominal'] : 0;
                    $hargaSetelahDiskon = max(0, $item['price'] - $nominalDiskon);
                    $subtotalItem       = $hargaSetelahDiskon * $item['qty'];
                    $totalDiskonKeseluruhan += $nominalDiskon * $item['qty'];
                ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td>
                            <?php if (!empty($discount)) : ?>
                                <span class="text-muted text-decoration-line-through" style="font-size:0.85em;">
                                    IDR <?= number_format($item['price'], 0, ',', '.') ?>
                                </span><br>
                                <span class="text-danger">IDR <?= number_format($hargaSetelahDiskon, 0, ',', '.') ?></span>
                            <?php else : ?>
                                IDR <?= number_format($item['price'], 0, ',', '.') ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['qty'] ?></td>
                        <td>IDR <?= number_format($subtotalItem, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                    <td>IDR <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="fw-bold">IDR <?= number_format($total + 11000, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?= form_close() ?>

<?= $this->endSection() ?>
