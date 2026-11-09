<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="main-container" class="main">
  <div class="module--default">
    <h1><?= $page->title()->html() ?></h1>
    <?= $page->text()->kirbytext() ?>
  </div>
</main>

<?php snippet('footer') ?>
