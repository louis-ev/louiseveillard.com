<?php
/**
 * Project list (numbers and titles), sortable by category from the home
 * intro links, with a full-screen cover shown on hover (see main.js)
 */

$categories = [
  'sites' => 'Sites web',
  'objets-interactifs' => 'Objets interactifs',
  'vr' => 'Projets en réalité virtuelle',
  'outils-pedagogiques' => 'Outils pédagogiques',
  'autres' => 'Autres projets',
];
$order = array_flip(array_keys($categories));

$projets = page('projets')->children()->listed();
?>
<section class="module--projetList">
  <ol class="module--projetList--titles" id="projets-liste" aria-label="Projets">
    <li class="module--projetList--heading isotope--item" data-index="0" data-num="0"><h3>Derniers projets</h3></li>
    <?php foreach ($categories as $slug => $name): ?>
    <li class="module--projetList--heading isotope--item is--hidden" aria-hidden="true" data-type="<?= $slug ?>" data-index="0" data-num="<?= $order[$slug] + 1 ?>"><h3><?= $name ?></h3></li>
    <?php endforeach ?>

    <?php foreach ($projets as $p): ?>
    <li class="module--projetList--titles--projetName isotope--item<?= r($p->isOpen(), ' is--active') ?>" data-index="<?= $p->projetIndex() ?>" data-type="<?= $p->rubrique() ?>" data-num="<?= ($order[$p->rubrique()] ?? count($order)) + 1 ?>">
      <a class="module--projetList--titles--projetName--links" href="<?= $p->url() ?>">
        <span class="module--projetList--titles--projetName--links--index"><?= $p->projetIndex() ?></span>
        <span class="module--projetList--titles--projetName--links--name"><?= $p->title()->html() ?></span>
        <span class="module--projetList--titles--projetName--links--corners"></span>
      </a>
    </li>
    <?php endforeach ?>
  </ol>

  <div class="module--projetList--visuelWrapper" aria-hidden="true">
    <?php foreach ($projets as $p): ?>
    <?php if ($image = $p->imageCover()->toFile()): ?>
    <?php $widths = array_unique([min(800, $image->width()), min(1200, $image->width()), min(1800, $image->width())]) ?>
    <img
      class="module--projetList--visuelWrapper--visuel"
      data-index="<?= $p->projetIndex() ?>"
      data-srcset="<?= implode(', ', array_map(fn ($w) => $image->thumb(['width' => $w, 'format' => 'webp'])->url() . ' ' . $w . 'w', $widths)) ?>"
      sizes="100vw"
      alt="">
    <?php endif ?>
    <?php endforeach ?>
    <div class="module--projetList--visuelWrapper--bgmask"></div>
  </div>
</section>
