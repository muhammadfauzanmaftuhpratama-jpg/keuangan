<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
    <div class="content-area">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Tabungan</h5>
                <p class="text-muted mb-0 small">Kelola target tabungan Anda</p>
            </div>
            <a href="/user/saving/create" class="btn btn-warning btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah
                Tabungan</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><i
                    class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button"
                    class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><i
                    class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?><button type="button"
                    class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

        <!-- Total + Search -->
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="card stat-card p-3" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff">
                    <div class="d-flex align-items-center gap-3"><i class="bi bi-piggy-bank fs-1"></i>
                        <div>
                            <div style="opacity:.85;font-size:.9rem">Total Semua Tabungan</div>
                            <div class="fw-bold fs-4"><?= rupiah($totalSaving) ?></div>
                            <div style="opacity:.7;font-size:.8rem"><?= $totalData ?> tabungan aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3 h-100 d-flex align-items-center justify-content-center">
                    <form method="GET" action="/user/saving" class="w-100">
                        <label class="form-label small fw-semibold mb-1">Cari Tabungan</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Nama tabungan..."
                                value="<?= esc($search) ?>">
                            <?php if ($search): ?><a href="/user/saving" class="btn btn-outline-secondary"><i
                                        class="bi bi-x"></i></a><?php endif; ?>
                            <button type="submit" class="btn btn-warning btn-sm">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($search): ?>
            <div class="alert alert-info py-2 mb-3"><i class="bi bi-search me-2"></i>Hasil:
                "<strong><?= esc($search) ?></strong>" — <?= $totalData ?> ditemukan</div><?php endif; ?>

        <?php if (count($savings) > 0): ?>
            <div class="row g-3">
                <?php foreach ($savings as $saving):
                    $percent = $saving['target_amount'] > 0 ? min(100, round($saving['current_amount'] / $saving['target_amount'] * 100)) : 0;
                    $isDone = $percent >= 100;
                    $daysLeft = $saving['deadline'] ? (int) ceil((strtotime($saving['deadline']) - time()) / 86400) : null;
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card stat-card p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0"><?= esc($saving['name']) ?></h6>
                                    <?php if ($saving['deadline']): ?>
                                        <small class="text-muted"><i
                                                class="bi bi-calendar me-1"></i><?= date('d M Y', strtotime($saving['deadline'])) ?>
                                            <?php if ($daysLeft !== null): ?> <span
                                                    class="<?= $daysLeft < 7 ? 'text-danger' : 'text-muted' ?>">(<?= $daysLeft > 0 ? $daysLeft . ' hari lagi' : 'Lewat' ?>)</span><?php endif; ?>
                                        </small><?php endif; ?>
                                </div>
                                <?php if ($isDone): ?><span class="badge bg-success">Tercapai!</span><?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small mb-1"><span
                                        class="text-muted">Progress</span><span class="fw-semibold"><?= $percent ?>%</span>
                                </div>
                                <div class="progress" style="height:8px">
                                    <div class="progress-bar <?= $isDone ? 'bg-success' : 'bg-warning' ?>"
                                        style="width:<?= $percent ?>%"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between small mb-3">
                                <div>
                                    <div class="text-muted">Terkumpul</div>
                                    <div class="fw-semibold text-warning"><?= rupiah($saving['current_amount']) ?></div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted">Target</div>
                                    <div class="fw-semibold"><?= rupiah($saving['target_amount']) ?></div>
                                </div>
                            </div>
                            <div class="d-flex gap-1 mt-auto">
                                <a href="/user/saving/detail/<?= $saving['id'] ?>" class="btn btn-primary btn-sm flex-fill"><i
                                        class="bi bi-eye me-1"></i>Detail</a>
                                <a href="/user/saving/edit/<?= $saving['id'] ?>" class="btn btn-warning btn-sm"><i
                                        class="bi bi-pencil"></i></a>
                                <button class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('/user/saving/delete/<?= $saving['id'] ?>', '<?= esc($saving['name']) ?>')"><i
                                        class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalData > $perPage): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">Menampilkan <?= count($savings) ?> dari <?= $totalData ?> tabungan</small>
                    <?= $pager->links('default', 'bootstrap_pagination') ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-piggy-bank fs-1 d-block mb-2"></i>
                <?= $search ? 'Tidak ada tabungan yang cocok dengan pencarian' : 'Belum ada target tabungan.' ?>
                <?php if (!$search): ?><br><a href="/user/saving/create">Buat sekarang</a><?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Hapus
                    Tabungan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Yakin ingin menghapus tabungan <strong id="deleteName"></strong>?</p>
                <p class="small text-danger"><i class="bi bi-exclamation-circle me-1"></i>Semua riwayat
                    setoran/penarikan juga akan terhapus!</p>
            </div>
            <div class="modal-footer border-0"><button type="button" class="btn btn-light"
                    data-bs-dismiss="modal">Batal</button><a href="#" id="deleteBtn" class="btn btn-danger"><i
                        class="bi bi-trash me-1"></i>Hapus</a></div>
        </div>
    </div>
</div>
<script>function confirmDelete(url, name) { document.getElementById('deleteName').textContent = name; document.getElementById('deleteBtn').href = url; new bootstrap.Modal(document.getElementById('deleteModal')).show(); }</script>
<?= view('user/layouts/footer') ?>