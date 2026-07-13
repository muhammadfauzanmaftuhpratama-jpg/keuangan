<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Laporan Tahunan <?= $year ?></h5><p class="text-muted mb-0 small">Ringkasan keuangan sepanjang tahun</p></div>
<a href="/admin/report" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Laporan Bulanan</a>
</div>

<div class="card stat-card p-3 mb-4">
<form method="GET" action="/admin/report/yearly" class="row g-2 align-items-end">
<div class="col-auto"><label class="form-label small fw-semibold mb-1">Tahun</label>
<select name="year" class="form-select form-select-sm">
<?php for($y=date('Y');$y>=date('Y')-5;$y--): ?><option value="<?= $y ?>" <?= $year==$y?'selected':'' ?>><?= $y ?></option><?php endfor; ?>
</select></div>
<div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Tampilkan</button></div>
</form>
</div>

<div class="row g-3 mb-4">
<div class="col-md-4"><div class="card stat-card p-3" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff">
<div class="d-flex align-items-center gap-3"><i class="bi bi-arrow-down-circle fs-2"></i>
<div><div style="opacity:.85;font-size:.85rem">Total Pemasukan <?= $year ?></div><div class="fw-bold fs-5"><?= rupiah($totalYearIncome) ?></div></div></div></div></div>
<div class="col-md-4"><div class="card stat-card p-3" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff">
<div class="d-flex align-items-center gap-3"><i class="bi bi-arrow-up-circle fs-2"></i>
<div><div style="opacity:.85;font-size:.85rem">Total Pengeluaran <?= $year ?></div><div class="fw-bold fs-5"><?= rupiah($totalYearExpense) ?></div></div></div></div></div>
<div class="col-md-4"><div class="card stat-card p-3" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff">
<div class="d-flex align-items-center gap-3"><i class="bi bi-wallet2 fs-2"></i>
<div><div style="opacity:.85;font-size:.85rem">Saldo Tahunan <?= $year ?></div><div class="fw-bold fs-5"><?= rupiah($totalYearBalance) ?></div></div></div></div></div>
</div>

<div class="card stat-card p-3 mb-4">
<h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2 text-primary"></i>Grafik Pemasukan vs Pengeluaran <?= $year ?></h6>
<canvas id="yearlyChart" height="80"></canvas>
</div>

<div class="card stat-card"><div class="card-body p-0">
<div class="table-responsive"><table class="table table-hover mb-0">
<thead class="table-light"><tr><th>Bulan</th><th>Pemasukan</th><th>Pengeluaran</th><th>Saldo</th><th>Status</th></tr></thead>
<tbody>
<?php foreach($monthlyData as $row): ?>
<tr>
<td class="fw-semibold"><?= $row['month_name'] ?></td>
<td class="text-success fw-semibold"><?= rupiah($row['income']) ?></td>
<td class="text-danger fw-semibold"><?= rupiah($row['expense']) ?></td>
<td class="fw-semibold <?= $row['balance']<0?'text-danger':'text-primary' ?>"><?= rupiah($row['balance']) ?></td>
<td><?php if($row['income']==0&&$row['expense']==0):?><span class="badge bg-secondary">Kosong</span><?php elseif($row['balance']>=0):?><span class="badge bg-success">Surplus</span><?php else:?><span class="badge bg-danger">Defisit</span><?php endif;?></td>
</tr>
<?php endforeach; ?>
<tr class="table-light fw-bold"><td>TOTAL</td><td class="text-success"><?= rupiah($totalYearIncome) ?></td><td class="text-danger"><?= rupiah($totalYearExpense) ?></td><td class="<?= $totalYearBalance<0?'text-danger':'text-primary' ?>"><?= rupiah($totalYearBalance) ?></td><td><span class="badge <?= $totalYearBalance>=0?'bg-success':'bg-danger' ?>"><?= $totalYearBalance>=0?'Surplus':'Defisit' ?></span></td></tr>
</tbody>
</table></div></div></div>

</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    new Chart(document.getElementById('yearlyChart').getContext('2d'),{
        type:'bar',
        data:{labels:<?= $chartLabels ?>,datasets:[{label:'Pemasukan',data:<?= $chartIncome ?>,backgroundColor:'rgba(16,185,129,.75)',borderRadius:6},{label:'Pengeluaran',data:<?= $chartExpense ?>,backgroundColor:'rgba(239,68,68,.75)',borderRadius:6}]},
        options:{responsive:true,plugins:{legend:{position:'top'},tooltip:{callbacks:{label:function(c){return' Rp '+c.raw.toLocaleString('id-ID')}}}},scales:{y:{beginAtZero:true,ticks:{callback:function(v){if(v>=1000000)return'Rp '+(v/1000000).toFixed(1)+'jt';if(v>=1000)return'Rp '+(v/1000).toFixed(0)+'rb';return'Rp '+v}}}}}
    });
});
</script>
<?= view('admin/layouts/footer') ?>
