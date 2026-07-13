<?= view('admin/layouts/header') ?>
<?= view('admin/layouts/sidebar') ?>
<div id="main-content"><?= view('admin/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Pemasukan</h5><p class="text-muted mb-0 small">Kelola data Pemasukan Anda</p></div>
<a href="/admin/income/create" class="btn btn-success btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Pemasukan</a>
</div>

<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show py-2"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="card stat-card p-3 mb-3">
<form method="GET" action="/admin/income" class="row g-2 align-items-end">
<div class="col-md-2 col-sm-6"><label class="form-label small fw-semibold mb-1">Bulan</label>
<select name="month" class="form-select form-select-sm">
<?php for($m=1;$m<=12;$m++): ?>
<option value="<?= str_pad($m,2,'0',STR_PAD_LEFT) ?>" <?= $month==str_pad($m,2,'0',STR_PAD_LEFT)?'selected':'' ?>><?= date('F',mktime(0,0,0,$m,1)) ?></option>
<?php endfor; ?>
</select></div>
<div class="col-md-2 col-sm-6"><label class="form-label small fw-semibold mb-1">Tahun</label>
<select name="year" class="form-select form-select-sm">
<?php for($y=date('Y');$y>=date('Y')-3;$y--): ?>
<option value="<?= $y ?>" <?= $year==$y?'selected':'' ?>><?= $y ?></option>
<?php endfor; ?>
</select></div>
<div class="col-md-4"><label class="form-label small fw-semibold mb-1">Cari</label>
<div class="input-group input-group-sm">
<span class="input-group-text"><i class="bi bi-search"></i></span>
<input type="text" name="search" class="form-control" placeholder="Kategori atau catatan..." value="<?= esc($search) ?>">
<?php if($search): ?><a href="/admin/income?month=<?= $month ?>&year=<?= $year ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a><?php endif; ?>
</div></div>
<div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button></div>
<div class="col-auto ms-auto"><div class="text-end"><div class="small text-muted">Total Pemasukan</div><div class="fw-bold text-success"><?= rupiah($total) ?></div></div></div>
</form>
</div>

<?php if($search): ?><div class="alert alert-info py-2 mb-3 small"><i class="bi bi-search me-2"></i>Hasil: "<strong><?= esc($search) ?></strong>" — <?= $totalData ?> data</div><?php endif; ?>

<div class="card stat-card"><div class="card-body p-0">
<div class="table-responsive">
<table class="table table-hover mb-0">
<thead class="table-light"><tr><th>#</th><th>Kategori</th><th>Nominal</th><th>Tanggal</th><th>Catatan</th><th>Aksi</th></tr></thead>
<tbody>
<?php if(count($transactions)>0): foreach($transactions as $i=>$trx): ?>
<tr>
<td class="small"><?= ($pager->getCurrentPage()-1)*$perPage+$i+1 ?></td>
<td><span class="badge bg-success bg-opacity-10 text-success"><?= esc($trx['category']) ?></span></td>
<td class="fw-semibold text-success"><?= $trx['type']==='income'?'+':'-' ?><?= rupiah($trx['amount']) ?></td>
<td class="small"><?= date('d M Y',strtotime($trx['date'])) ?></td>
<td class="text-muted small"><?= $trx['note']?esc($trx['note']):'—' ?></td>
<td>
<a href="/admin/income/edit/<?= $trx['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
<button class="btn btn-danger btn-sm" onclick="if(confirm('Hapus data ini?'))window.location='/admin/income/delete/<?= $trx['id'] ?>'"><i class="bi bi-trash"></i></button>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i><?= $search?'Tidak ada data yang cocok':'Belum ada data Pemasukan bulan ini' ?></td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<?php if($totalData>$perPage): ?>
<div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
<small class="text-muted">Menampilkan <?= count($transactions) ?> dari <?= $totalData ?> data</small>
<?= $pager->links('default','bootstrap_pagination') ?>
</div>
<?php endif; ?>
</div></div>

</div></div>
<?= view('admin/layouts/footer') ?>
