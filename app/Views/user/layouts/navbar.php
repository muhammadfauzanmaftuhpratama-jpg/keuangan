<div id="navbar">
<div class="d-flex align-items-center gap-3">
<button id="sidebarToggle" class="btn btn-sm btn-light d-md-none"><i class="bi bi-list fs-5"></i></button>
<div>
<h6 class="mb-0 fw-semibold"><?= $title ?? 'Dashboard' ?></h6>
</div>
</div>
<div class="d-flex align-items-center gap-3">
<span class="text-muted small d-none d-md-inline"><i class="bi bi-calendar me-1"></i><?= date('d F Y') ?></span>
<?php
$notifModel = new \App\Models\NotificationModel();
$unread = $notifModel->countUnread((int)session()->get('user_id'));
?>
<a href="/user/notifications" class="btn btn-sm btn-light position-relative">
<i class="bi bi-bell"></i>
<?php if($unread > 0): ?>
<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem"><?= $unread ?></span>
<?php endif; ?>
</a>
<div class="dropdown">
<button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
<?php if(session()->get('avatar')): ?>
<img src="<?= base_url('writable/uploads/avatars/'.session()->get('avatar')) ?>" class="rounded-circle" width="28" height="28" style="object-fit:cover">
<?php else: ?>
<div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:28px;height:28px;background:linear-gradient(135deg,#667eea,#764ba2);font-size:.75rem"><?= strtoupper(substr(session()->get('name'),0,1)) ?></div>
<?php endif; ?>
<span class="d-none d-md-inline small"><?= esc(session()->get('name')) ?></span>
</button>
<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item small" href="/user/profile"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item small text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
</ul>
</div>
</div>
</div>
