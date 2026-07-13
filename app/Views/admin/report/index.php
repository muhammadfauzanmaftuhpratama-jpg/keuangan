<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
<div><h5 class="fw-bold mb-1">Laporan Keuangan</h5><p class="text-muted mb-0 small">Rekap keuangan bulanan Anda</p></div>
<div class="d-flex gap-2 flex-wrap">
<a href="/admin/report/export-pdf?month=<?= $month ?>&year=<?= $year ?>" class="btn btn-danger btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
<a href="/admin/report/export-excel?month=<?= $month ?>&year=<?= $year ?>" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a>
<a href="/admin/report/export-csv?month=<?= $month ?>&year=<?= $year ?>" class="btn btn-secondary btn-sm"><i class="bi bi-file-earmark-text me-1"></i>CSV</a>
<a href="/admin/report/yearly?year=<?= $year ?>" class="btn btn-info btn-sm text-white"><i class="bi bi-calendar3 me-1"></i>Tahunan</a>
</div>
</div>

<div class="card stat-card p-3 mb-4">
<form method="GET" action="/admin/report" class="row g-2 align-items-end">
<div class="col-auto"><label class="form-label small fw-semibold mb-1">Bulan</label>
<select name="month" class="form-select form-select-sm">
<?php for($m=1;$m<=12;$m++): ?><option value="<?= str_pad($m,2,'0',STR_PAD_LEFT) ?>" <?= $month==str_pad($m,2,'0',STR_PAD_LEFT)?'selected':'' ?>><?= date('F',mktime(0,0,0,$m,1)) ?></option><?php endfor; ?>
</select></div>
<div class="col-auto"><label class="form-label small fw-semibold mb-1">Tahun</label>
<select name="year" class="form-select form-select-sm">
<?php for($y=date('Y');$y>=date('Y')-3;$y--): ?><option value="<?= $y ?>" <?= $year==$y?'selected':'' ?>><?= $y ?></option><?php endfor; ?>
</select></div>
<div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Tampilkan</button></div>
</form>
</div>

<?php $balance=$totalIncome-$totalExpense; ?>
<div class="row g-3 mb-4">
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3 border-start border-success border-4">
<div class="small text-muted">Total Pemasukan</div><div class="fw-bold fs-5 text-success"><?= rupiah($totalIncome) ?></div>
<div class="small mt-1"><?php $ic=$incomeChange;if($ic>0):?><span class="text-success"><i class="bi bi-arrow-up-short"></i><?= $ic ?>% vs <?= $prevMonthName ?></span><?php elseif($ic<0):?><span class="text-danger"><i class="bi bi-arrow-down-short"></i><?= abs($ic) ?>% vs <?= $prevMonthName ?></span><?php else:?><span class="text-muted">— sama dengan <?= $prevMonthName ?></span><?php endif;?>
</div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3 border-start border-danger border-4">
<div class="small text-muted">Total Pengeluaran</div><div class="fw-bold fs-5 text-danger"><?= rupiah($totalExpense) ?></div>
<div class="small mt-1"><?php $ec=$expenseChange;if($ec>0):?><span class="text-danger"><i class="bi bi-arrow-up-short"></i><?= $ec ?>% vs <?= $prevMonthName ?></span><?php elseif($ec<0):?><span class="text-success"><i class="bi bi-arrow-down-short"></i><?= abs($ec) ?>% vs <?= $prevMonthName ?></span><?php else:?><span class="text-muted">— sama dengan <?= $prevMonthName ?></span><?php endif;?>
</div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3 border-start border-primary border-4">
<div class="small text-muted">Saldo</div><div class="fw-bold fs-5 <?= $balance<0?'text-danger':'text-primary' ?>"><?= rupiah($balance) ?></div>
<div class="small text-muted">Pemasukan - Pengeluaran</div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3 border-start border-warning border-4">
<div class="small text-muted">Total Tabungan</div><div class="fw-bold fs-5 text-warning"><?= rupiah($totalSaving) ?></div>
<div class="small text-muted"><?= count($savings) ?> target aktif</div></div></div>
</div>

<div class="card stat-card mb-3"><div class="card-header bg-success bg-opacity-10 py-2 d-flex justify-content-between"><h6 class="fw-bold mb-0 text-success small"><i class="bi bi-arrow-down-circle me-2"></i>Detail Pemasukan — <?= $monthName.' '.$year ?></h6><span class="badge bg-success"><?= count($incomes) ?></span></div>
<div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>#</th><th>Kategori</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th></tr></thead>
<tbody>
<?php if(count($incomes)>0):foreach($incomes as $i=>$t):?>
<tr><td><?=$i+1?></td><td><?=esc($t['category'])?></td><td class="text-success fw-semibold"><?=rupiah($t['amount'])?></td><td><?=date('d M Y',strtotime($t['date']))?></td><td class="text-muted"><?=$t['note']?esc($t['note']):'—'?></td></tr>
<?php endforeach;?>
<tr class="table-success fw-bold"><td colspan="2">TOTAL</td><td><?=rupiah($totalIncome)?></td><td colspan="2"></td></tr>
<?php else:?><tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data pemasukan</td></tr><?php endif;?>
</tbody></table></div></div></div>

<div class="card stat-card mb-3"><div class="card-header bg-danger bg-opacity-10 py-2 d-flex justify-content-between"><h6 class="fw-bold mb-0 text-danger small"><i class="bi bi-arrow-up-circle me-2"></i>Detail Pengeluaran — <?= $monthName.' '.$year ?></h6><span class="badge bg-danger"><?= count($expenses) ?></span></div>
<div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>#</th><th>Kategori</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th></tr></thead>
<tbody>
<?php if(count($expenses)>0):foreach($expenses as $i=>$t):?>
<tr><td><?=$i+1?></td><td><?=esc($t['category'])?></td><td class="text-danger fw-semibold"><?=rupiah($t['amount'])?></td><td><?=date('d M Y',strtotime($t['date']))?></td><td class="text-muted"><?=$t['note']?esc($t['note']):'—'?></td></tr>
<?php endforeach;?>
<tr class="table-danger fw-bold"><td colspan="2">TOTAL</td><td><?=rupiah($totalExpense)?></td><td colspan="2"></td></tr>
<?php else:?><tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data pengeluaran</td></tr><?php endif;?>
</tbody></table></div></div></div>

<div class="card stat-card"><div class="card-header bg-warning bg-opacity-10 py-2"><h6 class="fw-bold mb-0 text-warning small"><i class="bi bi-credit-card me-2"></i>Rekap Hutang</h6></div>
<div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0 small">
<thead class="table-light"><tr><th>#</th><th>Kreditur</th><th>Total</th><th>Sisa</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
<tbody>
<?php if(count($debts)>0):foreach($debts as $i=>$d):?>
<tr><td><?=$i+1?></td><td><?=esc($d['creditor_name'])?></td><td><?=rupiah($d['total_amount'])?></td><td class="text-danger fw-semibold"><?=rupiah($d['remaining_amount'])?></td><td><?=date('d M Y',strtotime($d['due_date']))?></td><td><?=$d['status']==='paid'?'<span class="badge bg-success">Lunas</span>':'<span class="badge bg-danger">Belum</span>'?></td></tr>
<?php endforeach;else:?><tr><td colspan="6" class="text-center py-3 text-muted">Tidak ada data hutang</td></tr><?php endif;?>
</tbody></table></div></div></div>

</div></div>
<?= view('admin/layouts/footer') ?>
