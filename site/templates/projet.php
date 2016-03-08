<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">

	<article class="module--projet module--projet_full" data-type="<?= str::slug( $page->type()); ?>" data-visuel>
		<?= snippet( "projet--header", array( 'p' => $page)); ?>
		<section class="module--projet--text">
			<?php echo $page->text()->kirbytext() ?>
		</section>
	</article>

	<?php snippet( "module--projetList"); ?>

  <div class="module--projet_short" data-chargement="au_survol">
    <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
  		<div class="module--projet">
  			<?php snippet( "projet--header", array( 'p' => $p)); ?>
  		</div>
  	<?php endforeach ?>
  </div>
</main>
<?php snippet('footer') ?>