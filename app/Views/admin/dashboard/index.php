<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">

<div class="mb-4">
<h5 class="fw-bold mb-1">Selamat Datang, <?= esc(session()->get('name')) ?>! 👋</h5>
<p class="text-muted mb-0 small">Ringkasan keuangan bulan <strong><?= $monthName . ' ' . $year ?></strong></p>
</div>

<!-- Filter -->
<div class="card stat-card p-3 mb-4">
<form method="GET" action="/admin/dashboard" class="row g-2 align-items-end">
<div class="col-auto"><label class="form-label small fw-semibold mb-1">Bulan</label>
<select name="month" class="form-select form-select-sm">
<?php for($m=1;$m<=12;$m++): ?>
<option value="<?= str_pad($m,2,'0',STR_PAD_LEFT) ?>" <?= $month==str_pad($m,2,'0',STR_PAD_LEFT)?'selected':'' ?>><?= date('F',mktime(0,0,0,$m,1)) ?></option>
<?php endfor; ?>
</select></div>
<div class="col-auto"><label class="form-label small fw-semibold mb-1">Tahun</label>
<select name="year" class="form-select form-select-sm">
<?php for($y=date('Y');$y>=date('Y')-3;$y--): ?>
<option value="<?= $y ?>" <?= $year==$y?'selected':'' ?>><?= $y ?></option>
<?php endfor; ?>
</select></div>
<div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Tampilkan</button></div>
</form>
</div>

<!-- Info Sistem -->
<div class="row g-3 mb-2"><div class="col-12"><small class="text-muted text-uppercase fw-semibold" style="letter-spacing:1px"><i class="bi bi-gear me-1"></i>Info Sistem</small></div></div>
<div class="row g-3 mb-4">
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3"><div class="d-flex align-items-center gap-3"><div class="rounded-3 p-2" style="background:#e0e7ff"><i class="bi bi-people fs-4" style="color:#667eea"></i></div><div><div class="text-muted small">Total User</div><div class="fw-bold fs-5"><?= $totalUsers ?></div></div></div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3"><div class="d-flex align-items-center gap-3"><div class="rounded-3 p-2" style="background:#e0e7ff"><i class="bi bi-menu-button-wide fs-4" style="color:#667eea"></i></div><div><div class="text-muted small">Menu Aktif</div><div class="fw-bold fs-5"><?= $totalMenus ?></div></div></div></div></div>
</div>
<div class="row g-3 mb-2"><div class="col-12"><small class="text-muted text-uppercase fw-semibold" style="letter-spacing:1px"><i class="bi bi-wallet2 me-1"></i>Keuangan Saya — <?= $monthName . ' ' . $year ?></small></div></div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3"><div class="d-flex align-items-center gap-3"><div class="rounded-3 p-2" style="background:#d1fae5"><i class="bi bi-arrow-down-circle fs-4" style="color:#10b981"></i></div><div><div class="text-muted small">Pemasukan</div><div class="fw-bold text-success"><?= rupiah($myIncome) ?></div></div></div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3"><div class="d-flex align-items-center gap-3"><div class="rounded-3 p-2" style="background:#fee2e2"><i class="bi bi-arrow-up-circle fs-4" style="color:#ef4444"></i></div><div><div class="text-muted small">Pengeluaran</div><div class="fw-bold text-danger"><?= rupiah($myExpense) ?></div></div></div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3"><div class="d-flex align-items-center gap-3"><div class="rounded-3 p-2" style="background:#e0e7ff"><i class="bi bi-wallet2 fs-4" style="color:#667eea"></i></div><div><div class="text-muted small">Saldo</div><div class="fw-bold <?= $myBalance<0?'text-danger':'text-primary' ?>"><?= rupiah($myBalance) ?></div></div></div></div></div>
<div class="col-md-3 col-sm-6"><div class="card stat-card p-3"><div class="d-flex align-items-center gap-3"><div class="rounded-3 p-2" style="background:#fef3c7"><i class="bi bi-piggy-bank fs-4" style="color:#f59e0b"></i></div><div><div class="text-muted small">Tabungan</div><div class="fw-bold text-warning"><?= rupiah($mySaving) ?></div></div></div></div></div>
</div>

<?php if($myDebt>0): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
<i class="bi bi-exclamation-triangle"></i>
<div>Total hutang aktif: <strong><?= rupiah($myDebt) ?></strong> — <a href="/admin/debt" class="alert-link">Lihat detail</a></div>
</div>
<?php endif; ?>

<!-- Chart + Recent -->
<div class="row g-3">
<div class="col-md-8">
<div class="card stat-card p-3">
<div class="d-flex justify-content-between align-items-center mb-3">
<h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Pemasukan vs Pengeluaran</h6>
<small class="text-muted"><?= $monthName . ' ' . $year ?></small>
</div>
<canvas id="chart" height="130"></canvas>
</div></div>
<div class="col-md-4">
<div class="card stat-card p-3 h-100">
<div class="d-flex justify-content-between align-items-center mb-3">
<h6 class="fw-bold mb-0 small"><i class="bi bi-clock-history me-2 text-success"></i>Transaksi Terbaru</h6>
<a href="/admin/income" class="small text-primary">Semua</a>
</div>
<?php if(count($recentTransactions)>0): foreach($recentTransactions as $trx): ?>
<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
<div class="d-flex align-items-center gap-2">
<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;background:<?= $trx['type']==='income'?'#d1fae5':'#fee2e2' ?>">
<i class="bi bi-<?= $trx['type']==='income'?'arrow-down':'arrow-up' ?>" style="color:<?= $trx['type']==='income'?'#10b981':'#ef4444' ?>;font-size:.75rem"></i>
</div>
<div><div class="small fw-semibold" style="font-size:.8rem"><?= esc($trx['category']) ?></div><div class="text-muted" style="font-size:.7rem"><?= date('d M',strtotime($trx['date'])) ?></div></div>
</div>
<div class="fw-semibold small <?= $trx['type']==='income'?'text-success':'text-danger' ?>" style="font-size:.8rem">
<?= $trx['type']==='income'?'+':'-' ?><?= rupiah($trx['amount']) ?>
</div></div>
<?php endforeach; else: ?>
<div class="text-center text-muted py-3 small"><i class="bi bi-inbox d-block fs-2 mb-1"></i>Belum ada transaksi</div>
<?php endif; ?>
</div></div>
</div>

</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    var ctx=document.getElementById('chart');
    if(!ctx)return;
    new Chart(ctx.getContext('2d'),{
        type:'bar',
        data:{labels:<?= $chartLabels ?>,datasets:[{label:'Pemasukan',data:<?= $chartIncome ?>,backgroundColor:'rgba(16,185,129,.75)',borderRadius:6,borderSkipped:false},{label:'Pengeluaran',data:<?= $chartExpense ?>,backgroundColor:'rgba(239,68,68,.75)',borderRadius:6,borderSkipped:false}]},
        options:{responsive:true,plugins:{legend:{position:'top'},tooltip:{callbacks:{label:function(c){return' Rp '+c.raw.toLocaleString('id-ID')}}}},scales:{y:{beginAtZero:true,ticks:{callback:function(v){if(v>=1000000)return'Rp '+(v/1000000).toFixed(1)+'jt';if(v>=1000)return'Rp '+(v/1000).toFixed(0)+'rb';return'Rp '+v}}}}}
    });
});
</script>
<?= view('admin/layouts/footer') ?>
