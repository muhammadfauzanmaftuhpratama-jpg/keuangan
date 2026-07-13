<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Backup Database</h5><p class="text-muted mb-0 small">Buat dan kelola backup database</p></div>
<a href="/admin/backup/download" class="btn btn-primary btn-sm"><i class="bi bi-download me-1"></i>Buat Backup Sekarang</a>
</div>
<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success py-2 alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-0">
<?php if(count($files)>0): ?>
<div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>Nama File</th><th>Ukuran</th><th>Tanggal Dibuat</th><th>Aksi</th></tr></thead>
<tbody>
<?php foreach($files as $file): ?>
<tr>
<td><i class="bi bi-file-earmark-zip text-warning me-2"></i><code class="small"><?= esc($file['name']) ?></code></td>
<td class="text-muted"><?= $file['size'] ?></td>
<td class="text-muted"><?= $file['created'] ?></td>
<td>
<button class="btn btn-danger btn-sm" onclick="confirmDelete('/admin/backup/delete/<?= urlencode($file['name']) ?>','<?= esc($file['name']) ?>')"><i class="bi bi-trash"></i></button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table></div>
<?php else: ?>
<div class="text-center py-5 text-muted"><i class="bi bi-database-x fs-1 d-block mb-2"></i>Belum ada file backup.<br><small>Klik "Buat Backup Sekarang" untuk membuat backup.</small></div>
<?php endif; ?>
</div></div>
</div></div>
<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
<div class="modal-header border-0"><h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Hapus Backup</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><p class="text-muted">Yakin hapus file <strong id="deleteName"></strong>?</p></div>
<div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><a href="#" id="deleteBtn" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Hapus</a></div>
</div></div></div>
<script>function confirmDelete(url,name){document.getElementById('deleteName').textContent=name;document.getElementById('deleteBtn').href=url;new bootstrap.Modal(document.getElementById('deleteModal')).show();}</script>
<?= view('admin/layouts/footer') ?>
