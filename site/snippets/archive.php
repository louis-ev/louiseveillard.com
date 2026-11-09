<?php if ($site->archive()->isNotEmpty()): ?>
<aside class="module--archive" role="note" hidden>
  <div class="module--archive--text"><?= $site->archive()->kirbytext() ?></div>
  <button type="button" class="module--archive--close" aria-label="Fermer">×</button>
</aside>
<?php endif ?>
