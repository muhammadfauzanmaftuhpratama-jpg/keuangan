<?php $pager->setSurroundCount(2); ?>
<nav><ul class="pagination pagination-sm mb-0">
<?php if($pager->hasPreviousPage()): ?>
<li class="page-item"><a class="page-link" href="<?= $pager->getFirstPageURI() ?>"><i class="bi bi-chevron-double-left"></i></a></li>
<li class="page-item"><a class="page-link" href="<?= $pager->getPreviousPageURI() ?>"><i class="bi bi-chevron-left"></i></a></li>
<?php endif; ?>
<?php foreach($pager->links() as $link): ?>
<li class="page-item <?= $link['active']?'active':'' ?>"><a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a></li>
<?php endforeach; ?>
<?php if($pager->hasNextPage()): ?>
<li class="page-item"><a class="page-link" href="<?= $pager->getNextPageURI() ?>"><i class="bi bi-chevron-right"></i></a></li>
<li class="page-item"><a class="page-link" href="<?= $pager->getLastPageURI() ?>"><i class="bi bi-chevron-double-right"></i></a></li>
<?php endif; ?>
</ul></nav>
