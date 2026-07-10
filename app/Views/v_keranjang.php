<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<?= form_open('keranjang/edit') ?>
<!-- Table Keranjang -->
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if (!empty($items)) :
            foreach ($items as $index => $item) :
                $nominalDiskon     = !empty($discount) ? $discount['nominal'] : 0;
                $hargaSetelahDiskon = max(0, $item['price'] - $nominalDiskon);
                $subtotalDiskon    = $hargaSetelahDiskon * $item['qty'];
        ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><img src="<?= base_url() . "img/" . $item['options']['foto'] ?>" width="100px"></td>
                    <td>
                        <?php if (!empty($discount)) : ?>
                            <span class="text-muted text-decoration-line-through" style="font-size:0.85em;">
                                IDR <?= number_format($item['price'], 0, ',', '.') ?>
                            </span><br>
                            <span class="text-danger fw-bold">IDR <?= number_format($hargaSetelahDiskon, 0, ',', '.') ?></span>
                        <?php else : ?>
                            <?= number_to_currency($item['price'], 'IDR') ?>
                        <?php endif; ?>
                    </td>
                    <td><input type="number" min="1" name="qty<?= $i++ ?>" class="form-control" value="<?= $item['qty'] ?>"></td>
                    <td>
                        <?php if (!empty($discount)) : ?>
                            <span class="fw-bold">IDR <?= number_format($subtotalDiskon, 0, ',', '.') ?></span>
                        <?php else : ?>
                            <?= number_to_currency($item['subtotal'], 'IDR') ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
        <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>
<div class="alert alert-info">
    <?php if (!empty($discount)) : ?>
        Total = IDR <?= number_format($total, 0, ',', '.') ?>
    <?php else : ?>
        <?= "Total = " . number_to_currency($total, 'IDR') ?>
    <?php endif; ?>
</div>
<button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
<a class="btn btn-warning" href="<?= base_url() ?>keranjang/clear">Kosongkan Keranjang</a>
<?= form_close() ?>
<?php if (!empty($items)) : ?>
<a class="btn btn-success mt-2" href="<?= base_url('keranjang/checkout') ?>">Selesai Belanja</a>
<?php endif; ?>
<?= $this->endSection() ?>