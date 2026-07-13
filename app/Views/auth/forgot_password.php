<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password — Keuangan Pribadi</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>body{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center}.card{border:none;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.2)}.btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);border:none}</style>
</head>
<body>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-5 col-lg-4">
<div class="card p-4">
<div class="text-center mb-4"><i class="bi bi-key fs-1 text-primary"></i><h4 class="mt-2 fw-bold">Lupa Password</h4><p class="text-muted small">Masukkan email untuk menerima link reset</p></div>
<?php if(session()->getFlashdata('success')): ?><div class="alert alert-success py-2"><?= session()->getFlashdata('success') ?></div><?php endif; ?>
<?php if(session()->getFlashdata('info')): ?><div class="alert alert-info py-2"><?= session()->getFlashdata('info') ?></div><?php endif; ?>
<?php if(session()->getFlashdata('errors')): ?><div class="alert alert-danger py-2"><ul class="mb-0 ps-3"><?php foreach(session()->getFlashdata('errors') as $e): ?><li class="small"><?= $e ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form action="/forgot-password" method="POST">
<?= csrf_field() ?>
<div class="mb-4"><label class="form-label fw-semibold small">Email</label><div class="input-group"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= old('email') ?>" required></div></div>
<button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-send me-2"></i>Kirim Link Reset</button>
</form>
<hr><p class="text-center mb-0 small"><a href="/login" class="text-primary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></p>
</div>
</div>
</div>
</div>
</body></html>
