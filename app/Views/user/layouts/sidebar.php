<div id="sidebar">
<div class="brand">
<div class="d-flex align-items-center gap-2">
<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2)"><i class="bi bi-wallet2 text-white"></i></div>
<div><h6>Keuangan Pribadi</h6><small><?= esc(session()->get('name')) ?></small></div>
</div>
</div>
<nav class="py-2">
<?php
$menuModel = new \App\Models\MenuModel();
$menus = $menuModel->getActiveMenus('user');
$currentUrl = '/' . ltrim(current_url(true)->getPath(), '/');
foreach($menus as $menu):
    $isActive = str_starts_with($currentUrl, $menu['url']);
?>
<a href="<?= $menu['url'] ?>" class="nav-link <?= $isActive?'active':'' ?>">
<i class="<?= $menu['icon'] ?>"></i> <?= esc($menu['name']) ?>
</a>
<?php endforeach; ?>
</nav>
<div class="p-3 mt-auto">
<a href="/logout" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
</div>
