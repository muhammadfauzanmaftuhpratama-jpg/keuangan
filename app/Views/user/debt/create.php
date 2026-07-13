<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex align-items-center gap-2 mb-4">
<a href="/user/debt" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
<h5 class="fw-bold mb-0">Tambah Hutang</h5>
</div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-4">
<form action="/user/debt/store" method="POST">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Nama Kreditur <span class="text-danger">*</span></label><input type="text" name="creditor_name" class="form-control" placeholder="contoh: Bank BCA" value="<?= old('creditor_name') ?>" required maxlength="100"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Total Hutang <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="total_amount" class="form-control" placeholder="0" min="1" value="<?= old('total_amount') ?>" required></div></div>
<div class="col-md-6"><label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label><input type="date" name="due_date" class="form-control" value="<?= old('due_date') ?>" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Catatan</label><input type="text" name="note" class="form-control" placeholder="Catatan (opsional)" value="<?= old('note') ?>"></div>
</div>
<hr>
<div class="d-flex gap-2">
<button type="submit" class="btn btn-danger"><i class="bi bi-save me-1"></i>Simpan</button>
<a href="/user/debt" class="btn btn-light">Batal</a>
</div>
</form>
</div></div>
</div></div>
<?= view('user/layouts/footer') ?>
