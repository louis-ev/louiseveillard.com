<?php
/**
 * Project cover and header (title, introduction, technical details)
 *
 * @var Kirby\Cms\Page $p
 */
?>
<?php if ($image = $p->imageCover()->toFile()): ?>
<a class="module--projet--visuel" href="<?= $image->url() ?>" target="_blank" tabindex="-1">
  <?= levFigure($image, [
    'link' => false,
    'sizes' => '100vw',
    'lazy' => $p->is(page()) === false,
  ]) ?>
</a>
<?php endif ?>

<header class="module--projet--header" data-index="<?= $p->projetIndex() ?>">
  <div class="module--projet--header--text">
    <span class="module--projet--header--text--index"><?= $p->projetIndex() ?></span>
    <h1 class="module--projet--header--text--name"><?= $p->title()->html() ?></h1>

    <hr>

    <div class="module--projet--header--text--colLeft">
      <?= $p->header()->kirbytext() ?>
    </div>
    <div class="module--projet--header--text--colRight">
      <?= $p->headercol2()->kirbytext() ?>
    </div>
  </div>
</header>
