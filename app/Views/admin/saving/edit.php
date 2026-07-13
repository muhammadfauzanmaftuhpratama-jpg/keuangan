<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex align-items-center gap-2 mb-4">
<a href="/admin/saving" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
<h5 class="fw-bold mb-0">Edit Tabungan</h5>
</div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-4">
<form action="/admin/saving/update/<?= $saving['id'] ?>" method="POST">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Nama Tabungan <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="<?= old('name',$saving['name']) ?>" required maxlength="100"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Target Nominal <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="target_amount" class="form-control" value="<?= old('target_amount',$saving['target_amount']) ?>" min="1" required></div></div>
<div class="col-md-6"><label class="form-label fw-semibold">Target Tanggal</label><input type="date" name="deadline" class="form-control" value="<?= old('deadline',$saving['deadline']) ?>"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Catatan</label><input type="text" name="note" class="form-control" value="<?= old('note',$saving['note']) ?>"></div>
</div>
<hr>
<div class="d-flex gap-2">
<button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
<a href="/admin/saving" class="btn btn-light">Batal</a>
</div>
</form>
</div></div>
</div></div>
<?= view('admin/layouts/footer') ?>
