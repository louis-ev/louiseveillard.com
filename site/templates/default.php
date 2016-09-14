<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">
	<div class="module--default">
    <h1><?php echo $page->title()->html() ?></h1>
	  <?php echo $page->text()->kirbytext() ?>
	</div>
</main>
<?php snippet('footer') ?>