<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex align-items-center gap-2 mb-4">
<a href="/user/debt" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i></a>
<h5 class="fw-bold mb-0">Detail Hutang — <?= esc($debt['creditor_name']) ?></h5>
</div>

<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show py-2"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>

<?php $paidPercent = $debt['total_amount'] > 0 ? min(100, round((($debt['total_amount']-$debt['remaining_amount'])/$debt['total_amount'])*100)) : 0; ?>
<div class="row g-3 mb-4">
<div class="col-md-8">
<div class="card stat-card p-4">
<div class="d-flex justify-content-between mb-3">
<div><h5 class="fw-bold mb-1"><?= esc($debt['creditor_name']) ?></h5><small class="text-muted"><i class="bi bi-calendar me-1"></i>Jatuh tempo: <?= date('d M Y',strtotime($debt['due_date'])) ?></small></div>
<?= $debt['status']==='paid'?'<span class="badge bg-success fs-6">Lunas 🎉</span>':'<span class="badge bg-danger fs-6">Belum Lunas</span>' ?>
</div>
<div class="row text-center mb-3">
<div class="col"><div class="text-muted small">Total Hutang</div><div class="fw-bold fs-5"><?= rupiah($debt['total_amount']) ?></div></div>
<div class="col"><div class="text-muted small">Sudah Dibayar</div><div class="fw-bold fs-5 text-success"><?= rupiah($debt['total_amount']-$debt['remaining_amount']) ?></div></div>
<div class="col"><div class="text-muted small">Sisa Hutang</div><div class="fw-bold fs-5 text-danger"><?= rupiah($debt['remaining_amount']) ?></div></div>
</div>
<div class="mb-1 d-flex justify-content-between small"><span>Progress Pembayaran</span><span class="fw-semibold"><?= $paidPercent ?>%</span></div>
<div class="progress" style="height:12px"><div class="progress-bar bg-success" style="width:<?= $paidPercent ?>%"></div></div>
</div>
</div>

<?php if($debt['status'] !== 'paid'): ?>
<div class="col-md-4">
<div class="card stat-card p-3">
<h6 class="fw-bold mb-3"><i class="bi bi-cash-coin text-success me-2"></i>Catat Pembayaran</h6>
<form action="/user/debt/pay/<?= $debt['id'] ?>" method="POST">
<?= csrf_field() ?>
<div class="mb-2"><label class="form-label small fw-semibold">Nominal Bayar</label><div class="input-group input-group-sm"><span class="input-group-text">Rp</span><input type="number" name="amount" class="form-control" placeholder="0" min="1" max="<?= $debt['remaining_amount'] ?>" required></div></div>
<div class="mb-2"><label class="form-label small fw-semibold">Tanggal Bayar</label><input type="date" name="payment_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required></div>
<div class="mb-2"><input type="text" name="note" class="form-control form-control-sm" placeholder="Catatan (opsional)"></div>
<button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-cash-coin me-1"></i>Bayar</button>
</form>
</div>
</div>
<?php endif; ?>
</div>

<!-- Riwayat Pembayaran -->
<div class="card stat-card"><div class="card-body p-0">
<div class="p-3 border-bottom"><h6 class="fw-bold mb-0">Riwayat Pembayaran</h6></div>
<div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>#</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th></tr></thead>
<tbody>
<?php if(count($payments)>0): foreach($payments as $i=>$p): ?>
<tr><td><?= $i+1 ?></td><td class="fw-semibold text-success"><?= rupiah($p['amount']) ?></td><td><?= date('d M Y',strtotime($p['payment_date'])) ?></td><td class="text-muted"><?= $p['note']?esc($p['note']):'—' ?></td></tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat pembayaran</td></tr>
<?php endif; ?>
</tbody>
</table></div>
</div></div>

</div></div>
<?= view('user/layouts/footer') ?>
