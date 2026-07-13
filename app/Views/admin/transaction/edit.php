<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex align-items-center gap-2 mb-4">
<a href="/user/<?= $type==='income'?'income':'expense' ?>" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
<div><h5 class="fw-bold mb-0"><?= $title ?></h5></div>
</div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-4">
<form action="/user/<?= $type==='income'?'income':'expense' ?>/update/<?= $transaction['id'] ?>" method="POST">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6">
<label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
<select name="category" class="form-select" required>
<?php foreach($categories as $cat): ?>
<option value="<?= esc($cat['name']) ?>" <?= (old('category',$transaction['category']))===$cat['name']?'selected':'' ?>><?= esc($cat['name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-6">
<label class="form-label fw-semibold">Nominal <span class="text-danger">*</span></label>
<div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="amount" class="form-control" value="<?= old('amount',$transaction['amount']) ?>" min="1" required></div>
</div>
<div class="col-md-6">
<label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
<input type="date" name="date" class="form-control" value="<?= old('date',$transaction['date']) ?>" required>
</div>
<div class="col-md-6">
<label class="form-label fw-semibold">Catatan</label>
<input type="text" name="note" class="form-control" value="<?= old('note',$transaction['note']) ?>" maxlength="255">
</div>
</div>
<hr>
<div class="d-flex gap-2">
<button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Update</button>
<a href="/user/<?= $type==='income'?'income':'expense' ?>" class="btn btn-light">Batal</a>
</div>
</form>
</div></div>
</div></div>
<?= view('admin/layouts/footer') ?>
