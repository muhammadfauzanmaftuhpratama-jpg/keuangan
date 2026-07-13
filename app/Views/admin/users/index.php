<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Manajemen User</h5><p class="text-muted mb-0 small">Daftar semua pengguna terdaftar</p></div>
<span class="badge bg-primary"><?= count($users) ?> User</span>
</div>
<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success py-2 alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<div class="card stat-card"><div class="card-body p-0">
<div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Login Terakhir</th><th>Terdaftar</th><th>Aksi</th></tr></thead>
<tbody>
<?php foreach($users as $i=>$user): ?>
<tr>
<td><?= $i+1 ?></td>
<td>
<div class="d-flex align-items-center gap-2">
<?php if($user['avatar']): ?><img src="<?= base_url('writable/uploads/avatars/'.$user['avatar']) ?>" class="rounded-circle" width="30" height="30" style="object-fit:cover">
<?php else: ?><div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:30px;height:30px;font-size:.75rem;background:linear-gradient(135deg,#667eea,#764ba2)"><?= strtoupper(substr($user['name'],0,1)) ?></div><?php endif; ?>
<span class="fw-semibold"><?= esc($user['name']) ?></span>
</div>
</td>
<td class="text-muted"><?= esc($user['email']) ?></td>
<td><?= $user['role']==='admin'?'<span class="badge bg-warning text-dark">Admin</span>':'<span class="badge bg-success">User</span>' ?></td>
<td class="text-muted"><?= $user['last_login']?date('d M Y H:i',strtotime($user['last_login'])):'—' ?></td>
<td class="text-muted"><?= date('d M Y',strtotime($user['created_at'])) ?></td>
<td>
<?php if($user['id'] != session()->get('user_id')): ?>
<button class="btn btn-danger btn-sm" onclick="confirmDelete('/admin/users/delete/<?= $user['id'] ?>','<?= esc($user['name']) ?>')"><i class="bi bi-trash"></i></button>
<?php else: ?><span class="text-muted small">Akun Anda</span><?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table></div></div></div>
</div></div>
<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
<div class="modal-header border-0"><h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Hapus User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><p class="text-muted">Yakin hapus user <strong id="deleteName"></strong>? Semua data keuangan user ini juga akan terhapus!</p></div>
<div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><a href="#" id="deleteBtn" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Hapus</a></div>
</div></div></div>
<script>function confirmDelete(url,name){document.getElementById('deleteName').textContent=name;document.getElementById('deleteBtn').href=url;new bootstrap.Modal(document.getElementById('deleteModal')).show();}</script>
<?= view('admin/layouts/footer') ?>
