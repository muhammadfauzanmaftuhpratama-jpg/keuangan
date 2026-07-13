<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex align-items-center gap-2 mb-4"><a href="/admin/category" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a><h5 class="fw-bold mb-0">Edit Kategori</h5></div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<?php if($category['is_default']): ?><div class="alert alert-info py-2 small"><i class="bi bi-info-circle me-2"></i>Kategori default: hanya icon dan warna yang bisa diubah.</div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-4">
<form action="/admin/category/update/<?= $category['id'] ?>" method="POST"><?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold small">Nama Kategori</label><input type="text" name="name" class="form-control" value="<?= esc(old('name',$category['name'])) ?>" <?= $category['is_default']?'readonly':'' ?> required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Tipe</label><input type="text" class="form-control" value="<?= $category['type']==='income'?'Pemasukan':'Pengeluaran' ?>" readonly></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Icon Bootstrap</label><div class="input-group"><span class="input-group-text"><i id="iconPreview" class="<?= esc($category['icon']) ?>"></i></span><input type="text" name="icon" id="iconInput" class="form-control" value="<?= esc(old('icon',$category['icon'])) ?>"></div></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Warna</label><div class="input-group"><input type="color" id="colorPicker" class="form-control form-control-color" value="<?= esc(old('color',$category['color'])) ?>" style="max-width:48px"><input type="text" name="color" id="colorText" class="form-control" value="<?= esc(old('color',$category['color'])) ?>" maxlength="7"></div></div>
</div>
<hr><div class="d-flex gap-2"><button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-save me-1"></i>Update</button><a href="/admin/category" class="btn btn-light btn-sm">Batal</a></div>
</form></div></div>
</div></div>
<script>document.getElementById('iconInput').addEventListener('input',function(){document.getElementById('iconPreview').className=this.value||'bi bi-tag';});var cp=document.getElementById('colorPicker');var ct=document.getElementById('colorText');cp.addEventListener('input',()=>{ct.value=cp.value;});ct.addEventListener('input',()=>{if(/^#[0-9A-Fa-f]{6}$/.test(ct.value))cp.value=ct.value;});</script>
<?= view('admin/layouts/footer') ?>