<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'Admin — Keuangan Pribadi' ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
:root{--sidebar-width:240px;--primary:#667eea;--primary-dark:#764ba2}
body{background:#f0f2f5;font-family:'Segoe UI',sans-serif}
#sidebar{width:var(--sidebar-width);background:linear-gradient(180deg,#1e293b 0%,#0f172a 100%);position:fixed;top:0;left:0;height:100vh;z-index:1000;overflow-y:auto;transition:transform .3s}
#sidebar .brand{padding:20px 16px;border-bottom:1px solid rgba(255,255,255,.1)}
#sidebar .brand h6{color:#fff;font-weight:700;margin:0}
#sidebar .brand small{color:rgba(255,255,255,.5);font-size:.7rem}
#sidebar .nav-link{color:rgba(255,255,255,.65);padding:10px 16px;border-radius:8px;margin:2px 8px;display:flex;align-items:center;gap:10px;font-size:.875rem;transition:all .2s}
#sidebar .nav-link:hover,#sidebar .nav-link.active{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
#sidebar .nav-link i{font-size:1rem;width:20px}
#main-content{margin-left:var(--sidebar-width);min-height:100vh;transition:margin .3s}
#navbar{background:#fff;padding:12px 24px;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:999;display:flex;align-items:center;justify-content:space-between}
.content-area{padding:24px}
.stat-card{background:#fff;border-radius:12px;border:none;box-shadow:0 1px 3px rgba(0,0,0,.05)}
@media(max-width:768px){#sidebar{transform:translateX(-100%)}#sidebar.show{transform:translateX(0)}#main-content{margin-left:0}}
</style>
