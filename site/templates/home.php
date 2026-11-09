<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="main-container" class="main">
  <div class="module--intro">
    <header class="module--intro--textblock">
      <?= $page->text()->kirbytext() ?>
    </header>
  </div>
</main>

<?php snippet('projets-liste') ?>

<?php snippet('footer') ?>
