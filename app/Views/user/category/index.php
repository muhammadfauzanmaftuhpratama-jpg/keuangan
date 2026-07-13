<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Kategori</h5><p class="text-muted mb-0 small">Kelola kategori pemasukan & pengeluaran</p></div>
<a href="/user/category/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori</a>
</div>
<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show py-2"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<div class="row g-4">
<div class="col-md-6">
<div class="card stat-card">
<div class="card-header bg-success bg-opacity-10 py-2 d-flex justify-content-between"><h6 class="fw-bold mb-0 text-success small"><i class="bi bi-arrow-down-circle me-2"></i>Kategori Pemasukan (<?= count($incomeCategories) ?>)</h6></div>
<div class="list-group list-group-flush">
<?php if(count($incomeCategories)>0): foreach($incomeCategories as $cat): ?>
<div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
<div class="d-flex align-items-center gap-2">
<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:<?= esc($cat['color']) ?>20"><i class="<?= esc($cat['icon']) ?>" style="color:<?= esc($cat['color']) ?>"></i></div>
<div><div class="fw-semibold small"><?= esc($cat['name']) ?></div><?php if($cat['is_default']): ?><small class="text-muted" style="font-size:.7rem">Default</small><?php else: ?><small class="text-primary" style="font-size:.7rem">Custom</small><?php endif; ?></div>
</div>
<div class="d-flex gap-1">
<a href="/user/category/edit/<?= $cat['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
<?php if(!$cat['is_default']): ?><button class="btn btn-danger btn-sm" onclick="confirmDelete('/user/category/delete/<?= $cat['id'] ?>','<?= esc($cat['name']) ?>')"><i class="bi bi-trash"></i></button><?php else: ?><button class="btn btn-secondary btn-sm" disabled><i class="bi bi-lock"></i></button><?php endif; ?>
</div></div>
<?php endforeach; else: ?><div class="text-center py-3 text-muted small">Belum ada kategori</div><?php endif; ?>
</div></div></div>
<div class="col-md-6">
<div class="card stat-card">
<div class="card-header bg-danger bg-opacity-10 py-2 d-flex justify-content-between"><h6 class="fw-bold mb-0 text-danger small"><i class="bi bi-arrow-up-circle me-2"></i>Kategori Pengeluaran (<?= count($expenseCategories) ?>)</h6></div>
<div class="list-group list-group-flush">
<?php if(count($expenseCategories)>0): foreach($expenseCategories as $cat): ?>
<div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
<div class="d-flex align-items-center gap-2">
<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:<?= esc($cat['color']) ?>20"><i class="<?= esc($cat['icon']) ?>" style="color:<?= esc($cat['color']) ?>"></i></div>
<div><div class="fw-semibold small"><?= esc($cat['name']) ?></div><?php if($cat['is_default']): ?><small class="text-muted" style="font-size:.7rem">Default</small><?php else: ?><small class="text-primary" style="font-size:.7rem">Custom</small><?php endif; ?></div>
</div>
<div class="d-flex gap-1">
<a href="/user/category/edit/<?= $cat['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
<?php if(!$cat['is_default']): ?><button class="btn btn-danger btn-sm" onclick="confirmDelete('/user/category/delete/<?= $cat['id'] ?>','<?= esc($cat['name']) ?>')"><i class="bi bi-trash"></i></button><?php else: ?><button class="btn btn-secondary btn-sm" disabled><i class="bi bi-lock"></i></button><?php endif; ?>
</div></div>
<?php endforeach; else: ?><div class="text-center py-3 text-muted small">Belum ada kategori</div><?php endif; ?>
</div></div></div>
</div>
</div></div>
<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
<div class="modal-header border-0"><h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Hapus Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><p class="text-muted mb-1">Yakin ingin menghapus kategori <strong id="deleteName"></strong>?</p><p class="small text-warning"><i class="bi bi-info-circle me-1"></i>Transaksi yang sudah menggunakan kategori ini tidak akan terhapus.</p></div>
<div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><a href="#" id="deleteBtn" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Hapus</a></div>
</div></div></div>
<script>function confirmDelete(url,name){document.getElementById('deleteName').textContent=name;document.getElementById('deleteBtn').href=url;new bootstrap.Modal(document.getElementById('deleteModal')).show();}</script>
<?= view('user/layouts/footer') ?>