<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4">
<div><h5 class="fw-bold mb-1">Notifikasi</h5><p class="text-muted mb-0 small"><?= $unreadCount ?> belum dibaca</p></div>
<?php if($unreadCount>0): ?>
<a href="/user/notifications/read-all" class="btn btn-sm btn-outline-primary"><i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca</a>
<?php endif; ?>
</div>

<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success py-2"><?= session()->getFlashdata('success') ?></div><?php endif; ?>

<div class="card stat-card"><div class="card-body p-0">
<?php if(count($notifications)>0): foreach($notifications as $notif): ?>
<div class="d-flex align-items-start gap-3 p-3 border-bottom <?= !$notif['is_read']?'bg-primary bg-opacity-5':'' ?>">
<div class="rounded-circle d-flex align-items-center justify-content-center mt-1 flex-shrink-0" style="width:36px;height:36px;background:<?= !$notif['is_read']?'#e0e7ff':'#f1f5f9' ?>">
<i class="bi bi-<?= !$notif['is_read']?'bell-fill text-primary':'bell text-muted' ?>"></i>
</div>
<div class="flex-grow-1">
<p class="mb-1 small <?= !$notif['is_read']?'fw-semibold':'' ?>"><?= esc($notif['message']) ?></p>
<small class="text-muted"><?= time_elapsed_string($notif['created_at']) ?></small>
</div>
<div class="d-flex gap-1 flex-shrink-0">
<?php if(!$notif['is_read']): ?>
<a href="/user/notifications/read/<?= $notif['id'] ?>" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 8px"><i class="bi bi-check"></i></a>
<?php endif; ?>
<a href="/user/notifications/delete/<?= $notif['id'] ?>" class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:2px 8px" onclick="return confirm('Hapus notifikasi ini?')"><i class="bi bi-trash"></i></a>
</div>
</div>
<?php endforeach; else: ?>
<div class="text-center py-5 text-muted"><i class="bi bi-bell-slash fs-1 d-block mb-2"></i>Tidak ada notifikasi</div>
<?php endif; ?>
</div></div>

<?php if(isset($pager)): ?>
<div class="d-flex justify-content-end mt-3"><?= $pager->links('default','bootstrap_pagination') ?></div>
<?php endif; ?>

</div></div>
<?= view('user/layouts/footer') ?>
