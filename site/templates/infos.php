<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="main-container" class="main">
  <div class="module--infos">
    <header class="module--infos--textblock">
      <?= $page->text()->kirbytext() ?>
    </header>
  </div>

  <section class="module--cv">
    <?php // one structure field per year, from the most recent ?>
    <?php foreach (range((int)date('Y'), 2010) as $year): ?>
    <?php $activities = $page->content()->get('activities' . $year) ?>
    <?php if ($activities->isNotEmpty()): ?>
    <div class="m_detail">
      <div class="m_detail--item">
        <div class="header">
          <h3><?= $year ?></h3>
        </div>
        <div class="description">
          <ul>
            <?php foreach ($activities->toStructure() as $activity): ?>
            <li><?= $activity->text()->kirbytext() ?></li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    </div>
    <?php endif ?>
    <?php endforeach ?>
  </section>
</main>

<?php snippet('projets-liste') ?>

<?php snippet('footer') ?>
