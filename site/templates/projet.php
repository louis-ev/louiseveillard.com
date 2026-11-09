<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="main-container" class="main">
  <article class="module--projet module--projet_full" data-type="<?= $page->rubrique() ?>">
    <?php snippet('projet-header', ['p' => $page]) ?>
    <section class="module--projet--text">
      <?= $page->text()->kirbytext() ?>
    </section>
  </article>
</main>

<?php snippet('projets-liste') ?>

<?php snippet('footer') ?>
