<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">
  <h1><?php echo $page->title()->html() ?></h1>

	<div class="module--default">
	  <?php echo $page->text()->kirbytext() ?>
	</div>
</main>
<?php snippet('footer') ?>