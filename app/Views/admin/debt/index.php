<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Hutang</h5><p class="text-muted mb-0 small">Pantau dan kelola hutang Anda</p></div>
<a href="/admin/debt/create" class="btn btn-danger btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Hutang</a>
</div>

<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<?php if($totalDebt > 0): ?>
<div class="card stat-card p-3 mb-4" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff">
<div class="d-flex align-items-center gap-3"><i class="bi bi-credit-card fs-1"></i>
<div><div style="opacity:.85;font-size:.9rem">Total Hutang Aktif</div><div class="fw-bold fs-4"><?= rupiah($totalDebt) ?></div></div>
</div></div>
<?php endif; ?>

<!-- Filter & Search -->
<div class="card stat-card p-3 mb-3">
<form method="GET" action="/admin/debt" class="row g-2 align-items-end">
<div class="col-md-5">
<label class="form-label small fw-semibold mb-1">Cari Kreditur</label>
<div class="input-group input-group-sm">
<span class="input-group-text"><i class="bi bi-search"></i></span>
<input type="text" name="search" class="form-control" placeholder="Nama kreditur atau catatan..." value="<?= esc($search) ?>">
<?php if($search): ?><a href="/admin/debt?status=<?= esc($status) ?>" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a><?php endif; ?>
</div>
</div>
<div class="col-md-3">
<label class="form-label small fw-semibold mb-1">Status</label>
<select name="status" class="form-select form-select-sm">
<option value="">Semua Status</option>
<option value="unpaid" <?= $status==='unpaid'?'selected':'' ?>>Belum Lunas</option>
<option value="paid"   <?= $status==='paid'?'selected':'' ?>>Lunas</option>
</select>
</div>
<div class="col-auto">
<button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
</div>
<div class="col-auto ms-auto">
<small class="text-muted">Total: <?= $totalData ?> hutang</small>
</div>
</form>
</div>

<div class="card stat-card">
<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-hover mb-0">
<thead class="table-light"><tr><th>#</th><th>Kreditur</th><th>Total Hutang</th><th>Sisa Hutang</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
<?php if(count($debts)>0): ?>
<?php foreach($debts as $i=>$debt):
$daysLeft    = (int)ceil((strtotime($debt['due_date'])-time())/86400);
$isAlmostDue = $daysLeft<=7 && $debt['status']==='unpaid';
?>
<tr class="<?= $isAlmostDue?'table-warning':'' ?>">
<td><?= ($pager->getCurrentPage()-1)*$perPage+$i+1 ?></td>
<td><div class="fw-semibold"><?= esc($debt['creditor_name']) ?></div><?php if($debt['note']): ?><small class="text-muted"><?= esc($debt['note']) ?></small><?php endif; ?></td>
<td><?= rupiah($debt['total_amount']) ?></td>
<td class="fw-semibold text-danger"><?= rupiah($debt['remaining_amount']) ?></td>
<td><?= date('d M Y',strtotime($debt['due_date'])) ?>
<?php if($isAlmostDue): ?><br><small class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i><?= $daysLeft>0?$daysLeft.' hari lagi':'Hari ini!' ?></small><?php endif; ?>
</td>
<td><?= $debt['status']==='paid'?'<span class="badge bg-success">Lunas</span>':'<span class="badge bg-danger">Belum Lunas</span>' ?></td>
<td>
<a href="/admin/debt/detail/<?= $debt['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
<a href="/admin/debt/edit/<?= $debt['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
<button class="btn btn-danger btn-sm" onclick="confirmDelete('/admin/debt/delete/<?= $debt['id'] ?>', '<?= esc($debt['creditor_name']) ?>')"><i class="bi bi-trash"></i></button>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="7" class="text-center py-4 text-muted">
<i class="bi bi-<?= ($search||$status)?'search':'emoji-smile' ?> fs-1 d-block mb-2"></i>
<?= ($search||$status)?'Tidak ada data yang cocok dengan filter':'Tidak ada hutang aktif' ?>
</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<?php if($totalData > $perPage): ?>
<div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
<small class="text-muted">Menampilkan <?= count($debts) ?> dari <?= $totalData ?> hutang</small>
<?= $pager->links('default', 'bootstrap_pagination') ?>
</div>
<?php endif; ?>
</div></div>

</div></div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content border-0 shadow">
<div class="modal-header border-0"><h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Hapus Hutang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><p class="text-muted">Yakin ingin menghapus hutang ke <strong id="deleteName"></strong>?</p><p class="small text-danger"><i class="bi bi-exclamation-circle me-1"></i>Semua riwayat pembayaran juga akan terhapus!</p></div>
<div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><a href="#" id="deleteBtn" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Hapus</a></div>
</div></div></div>
<script>function confirmDelete(url,name){document.getElementById('deleteName').textContent=name;document.getElementById('deleteBtn').href=url;new bootstrap.Modal(document.getElementById('deleteModal')).show();}</script>
<?= view('admin/layouts/footer') ?>
