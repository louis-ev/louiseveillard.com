<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">

	<div class="module--intro">
		<header class="module--into--textblock">
	  	<?php echo $page->text()->kirbytext() ?>
	  </div>
	</div>

	<?php snippet( "module--projetList"); ?>

  <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
		<div class="module--projet">
			<?php snippet( "projet--header", array( 'p' => $p)); ?>
		</div>
	<?php endforeach ?>
</main>
<?php snippet('footer') ?>