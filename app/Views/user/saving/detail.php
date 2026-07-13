<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex align-items-center gap-2 mb-4">
<a href="/user/saving" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
<h5 class="fw-bold mb-0"><?= esc($saving['name']) ?></h5>
</div>

<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show py-2"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>

<?php
$percent = $saving['target_amount'] > 0 ? min(100, round($saving['current_amount']/$saving['target_amount']*100)) : 0;
$isDone  = $percent >= 100;
?>
<div class="row g-3 mb-4">
<div class="col-md-8">
<div class="card stat-card p-4">
<div class="d-flex justify-content-between mb-3">
<div><h5 class="fw-bold mb-1"><?= esc($saving['name']) ?></h5><?php if($saving['deadline']): ?><small class="text-muted"><i class="bi bi-calendar me-1"></i>Target: <?= date('d M Y',strtotime($saving['deadline'])) ?></small><?php endif; ?></div>
<?php if($isDone): ?><span class="badge bg-success fs-6">Tercapai! 🎉</span><?php endif; ?>
</div>
<div class="row text-center mb-3">
<div class="col"><div class="text-muted small">Terkumpul</div><div class="fw-bold fs-5 text-warning"><?= rupiah($saving['current_amount']) ?></div></div>
<div class="col"><div class="text-muted small">Target</div><div class="fw-bold fs-5"><?= rupiah($saving['target_amount']) ?></div></div>
<div class="col"><div class="text-muted small">Kekurangan</div><div class="fw-bold fs-5 text-danger"><?= rupiah(max(0,$saving['target_amount']-$saving['current_amount'])) ?></div></div>
</div>
<div class="mb-1 d-flex justify-content-between small"><span>Progress</span><span class="fw-semibold"><?= $percent ?>%</span></div>
<div class="progress mb-0" style="height:12px"><div class="progress-bar <?= $isDone?'bg-success':'bg-warning' ?>" style="width:<?= $percent ?>%"></div></div>
</div>
</div>
<div class="col-md-4">
<div class="card stat-card p-3 mb-3">
<h6 class="fw-bold mb-3"><i class="bi bi-plus-circle text-success me-2"></i>Setor Dana</h6>
<form action="/user/saving/deposit/<?= $saving['id'] ?>" method="POST">
<?= csrf_field() ?>
<div class="mb-2"><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" name="amount" class="form-control" placeholder="Nominal" min="1" required></div></div>
<div class="mb-2"><input type="text" name="note" class="form-control form-control-sm" placeholder="Catatan (opsional)"></div>
<button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-plus-circle me-1"></i>Setor</button>
</form>
</div>
<div class="card stat-card p-3">
<h6 class="fw-bold mb-3"><i class="bi bi-dash-circle text-danger me-2"></i>Tarik Dana</h6>
<form action="/user/saving/withdraw/<?= $saving['id'] ?>" method="POST">
<?= csrf_field() ?>
<div class="mb-2"><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" name="amount" class="form-control" placeholder="Nominal" min="1" max="<?= $saving['current_amount'] ?>" required></div></div>
<div class="mb-2"><input type="text" name="note" class="form-control form-control-sm" placeholder="Catatan (opsional)"></div>
<button type="submit" class="btn btn-danger btn-sm w-100"><i class="bi bi-dash-circle me-1"></i>Tarik</button>
</form>
</div>
</div>
</div>

<!-- Riwayat -->
<div class="card stat-card"><div class="card-body p-0">
<div class="d-flex justify-content-between align-items-center p-3 border-bottom"><h6 class="fw-bold mb-0">Riwayat Transaksi</h6><small class="text-muted"><?= count($histories) ?> transaksi</small></div>
<div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>Tipe</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th></tr></thead>
<tbody>
<?php if(count($histories)>0): foreach($histories as $h): ?>
<tr>
<td><?= $h['type']==='deposit'?'<span class="badge bg-success">Setor</span>':'<span class="badge bg-danger">Tarik</span>' ?></td>
<td class="fw-semibold <?= $h['type']==='deposit'?'text-success':'text-danger' ?>"><?= $h['type']==='deposit'?'+':'-' ?><?= rupiah($h['amount']) ?></td>
<td><?= date('d M Y H:i',strtotime($h['created_at'])) ?></td>
<td class="text-muted"><?= $h['note']?esc($h['note']):'—' ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat transaksi</td></tr>
<?php endif; ?>
</tbody>
</table></div>
<?php if(isset($pager) && $totalData > $perPage): ?>
<div class="d-flex justify-content-end p-3 border-top"><?= $pager->links('history','bootstrap_pagination') ?></div>
<?php endif; ?>
</div></div>

</div></div>
<?= view('user/layouts/footer') ?>
