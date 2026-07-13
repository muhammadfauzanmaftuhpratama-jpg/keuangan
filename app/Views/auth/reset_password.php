<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password — Keuangan Pribadi</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>body{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center}.card{border:none;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.2)}.btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);border:none}</style>
</head>
<body>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-5 col-lg-4">
<div class="card p-4">
<div class="text-center mb-4"><i class="bi bi-lock-fill fs-1 text-primary"></i><h4 class="mt-2 fw-bold">Reset Password</h4><p class="text-muted small">Masukkan password baru Anda</p></div>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger py-2"><ul class="mb-0 ps-3"><?php foreach(session()->getFlashdata('errors') as $e): ?><li class="small"><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form action="/reset-password" method="POST">
<?= csrf_field() ?>
<input type="hidden" name="token" value="<?= esc($token) ?>">
<div class="mb-3"><label class="form-label fw-semibold small">Password Baru <small class="text-muted">(min. 8 karakter)</small></label><div class="input-group"><span class="input-group-text"><i class="bi bi-lock"></i></span><input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required></div></div>
<div class="mb-4"><label class="form-label fw-semibold small">Konfirmasi Password</label><div class="input-group"><span class="input-group-text"><i class="bi bi-lock-fill"></i></span><input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" required></div></div>
<button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-check-circle me-2"></i>Reset Password</button>
</form>
</div>
</div>
</div>
</div>
</body></html>
