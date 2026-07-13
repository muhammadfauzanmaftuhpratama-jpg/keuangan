<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex align-items-center gap-2 mb-4"><a href="/admin/menu" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a><h5 class="fw-bold mb-0">Edit Menu</h5></div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-4">
<form action="/admin/menu/update/<?= $menu['id'] ?>" method="POST"><?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold small">Nama Menu <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="<?= old('name',$menu['name']) ?>" required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Slug <span class="text-danger">*</span></label><input type="text" name="slug" class="form-control" value="<?= old('slug',$menu['slug']) ?>" placeholder="admin-menu" required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Icon <small class="text-muted">(Bootstrap Icons)</small></label><div class="input-group"><span class="input-group-text"><i id="iconPrev" class="bi bi-circle"></i></span><input type="text" name="icon" id="iconIn" class="form-control" value="<?= old('icon',$menu['icon'] ?? 'bi bi-circle') ?>" placeholder="bi bi-circle"></div></div>
<div class="col-md-6"><label class="form-label fw-semibold small">URL <span class="text-danger">*</span></label><input type="text" name="url" class="form-control" value="<?= old('url',$menu['url']) ?>" placeholder="/admin/xxx" required></div>
<div class="col-md-4"><label class="form-label fw-semibold small">Parent Menu</label><select name="parent_id" class="form-select"><option value="">— Tidak Ada —</option><?php foreach($parentMenus as $pm): ?><option value="<?= $pm['id'] ?>" <?= old('parent_id')==$pm['id']?'selected':'' ?>><?= esc($pm['name']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label fw-semibold small">Urutan</label><input type="number" name="sort_order" class="form-control" value="<?= old('sort_order',0) ?>" min="0"></div>
<div class="col-md-4"><label class="form-label fw-semibold small">Status</label><select name="is_active" class="form-select"><option value="1" <?= old('is_active','1')==='1'?'selected':'' ?>>Aktif</option><option value="0" <?= old('is_active')==='0'?'selected':'' ?>>Nonaktif</option></select></div>
</div>
<hr><div class="d-flex gap-2"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Simpan</button><a href="/admin/menu" class="btn btn-light btn-sm">Batal</a></div>
</form></div></div>
</div></div>
<script>document.getElementById('iconIn').addEventListener('input',function(){document.getElementById('iconPrev').className=this.value||'bi bi-circle';});</script>
<?= view('admin/layouts/footer') ?>
