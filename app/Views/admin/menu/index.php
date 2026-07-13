<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Manajemen Menu</h5><p class="text-muted mb-0 small">Kelola menu navigasi aplikasi</p></div>
<a href="/admin/menu/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Menu</a>
</div>
<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success py-2 alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-0">
<div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>#</th><th>Nama</th><th>Slug</th><th>Icon</th><th>URL</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
<?php if(count($menus)>0): foreach($menus as $i=>$menu): ?>
<tr>
<td><?= $i+1 ?></td>
<td class="fw-semibold"><i class="<?= esc($menu['icon']) ?> me-2"></i><?= esc($menu['name']) ?></td>
<td><code class="small"><?= esc($menu['slug']) ?></code></td>
<td><code class="small"><?= esc($menu['icon']) ?></code></td>
<td class="text-muted"><?= esc($menu['url']) ?></td>
<td><?= $menu['sort_order'] ?></td>
<td><?= $menu['is_active']?'<span class="badge bg-success">Aktif</span>':'<span class="badge bg-secondary">Nonaktif</span>' ?></td>
<td>
<a href="/admin/menu/edit/<?= $menu['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
<button class="btn btn-danger btn-sm" onclick="confirmDelete('/admin/menu/delete/<?= $menu['id'] ?>','<?= esc($menu['name']) ?>')"><i class="bi bi-trash"></i></button>
</td>
</tr>
<?php endforeach; else: ?><tr><td colspan="8" class="text-center py-3 text-muted">Belum ada menu</td></tr><?php endif; ?>
</tbody>
</table></div></div></div>
</div></div>
<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
<div class="modal-header border-0"><h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Hapus Menu</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><p class="text-muted">Yakin hapus menu <strong id="deleteName"></strong>?</p></div>
<div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><a href="#" id="deleteBtn" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Hapus</a></div>
</div></div></div>
<script>function confirmDelete(url,name){document.getElementById('deleteName').textContent=name;document.getElementById('deleteBtn').href=url;new bootstrap.Modal(document.getElementById('deleteModal')).show();}</script>
<?= view('admin/layouts/footer') ?>
