<?= view('user/layouts/header') ?>
<?= view('user/layouts/sidebar') ?>
<div id="main-content"><?= view('user/layouts/navbar') ?>
<div class="content-area">
<div class="mb-4"><h5 class="fw-bold mb-1">Profil Saya</h5><p class="text-muted mb-0 small">Kelola informasi akun Anda</p></div>

<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show py-2"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger py-2"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach(session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row g-4">
<div class="col-md-4">
<div class="card stat-card p-4 text-center">
<?php if($user['avatar']): ?>
<img src="<?= base_url('writable/uploads/avatars/'.$user['avatar']) ?>" class="rounded-circle mb-3 mx-auto d-block" style="width:100px;height:100px;object-fit:cover">
<?php else: ?>
<div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3" style="width:100px;height:100px;font-size:2.5rem;background:linear-gradient(135deg,#10b981,#059669)"><?= strtoupper(substr($user['name'],0,1)) ?></div>
<?php endif; ?>
<h5 class="fw-bold mb-1"><?= esc($user['name']) ?></h5>
<p class="text-muted small mb-1"><?= esc($user['email']) ?></p>
<span class="badge <?= $user['role']==='admin'?'bg-warning text-dark':'bg-success' ?>"><?= ucfirst($user['role']) ?></span>
<?php if($user['last_login']): ?><p class="text-muted small mt-2 mb-0">Login terakhir: <?= date('d M Y H:i',strtotime($user['last_login'])) ?></p><?php endif; ?>
</div>
</div>
<div class="col-md-8">
<div class="card stat-card p-4 mb-4">
<h6 class="fw-bold mb-3"><i class="bi bi-person-gear me-2 text-primary"></i>Edit Profil</h6>
<form action="/user/profile/update" method="POST" enctype="multipart/form-data">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold small">Nama Lengkap</label><input type="text" name="name" class="form-control" value="<?= esc(old('name',$user['name'])) ?>" required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Email</label><input type="email" name="email" class="form-control" value="<?= esc(old('email',$user['email'])) ?>" required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">No. Telepon</label><input type="text" name="phone" class="form-control" value="<?= esc(old('phone',$user['phone'])) ?>" placeholder="08xxxxxxxxxx"></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Foto Profil</label><input type="file" name="avatar" class="form-control" accept="image/*"><small class="text-muted">Maks 2MB, JPG/PNG/WEBP</small></div>
</div>
<hr><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
</form>
</div>
<div class="card stat-card p-4">
<h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-warning"></i>Ganti Password</h6>
<form action="/user/profile/change-password" method="POST">
<?= csrf_field() ?>
<div class="row g-3">
<div class="col-12"><label class="form-label fw-semibold small">Password Lama</label><input type="password" name="current_password" class="form-control" placeholder="Password lama" required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Password Baru <small class="text-muted">(min. 8 karakter)</small></label><input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter" required></div>
<div class="col-md-6"><label class="form-label fw-semibold small">Konfirmasi Password</label><input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" required></div>
</div>
<hr><button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-key me-1"></i>Ganti Password</button>
</form>
</div>
</div>
</div>
</div></div>
<?= view('user/layouts/footer') ?>
