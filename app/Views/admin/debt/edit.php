<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex align-items-center gap-2 mb-4">
<a href="/admin/debt" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
<h5 class="fw-bold mb-0">Edit Hutang</h5>
</div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-4">
<form action="/admin/debt/update/<?= $debt['id'] ?>" method="POST">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Nama Kreditur <span class="text-danger">*</span></label><input type="text" name="creditor_name" class="form-control" value="<?= old('creditor_name',$debt['creditor_name']) ?>" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Total Hutang</label><div class="input-group"><span class="input-group-text">Rp</span><input type="text" class="form-control" value="<?= rupiah($debt['total_amount']) ?>" readonly></div><small class="text-muted">Total hutang tidak bisa diubah</small></div>
<div class="col-md-6"><label class="form-label fw-semibold">Jatuh Tempo <span class="text-danger">*</span></label><input type="date" name="due_date" class="form-control" value="<?= old('due_date',$debt['due_date']) ?>" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Catatan</label><input type="text" name="note" class="form-control" value="<?= old('note',$debt['note']) ?>"></div>
</div>
<hr>
<div class="d-flex gap-2">
<button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
<a href="/admin/debt" class="btn btn-light">Batal</a>
</div>
</form>
</div></div>
</div></div>
<?= view('admin/layouts/footer') ?>
