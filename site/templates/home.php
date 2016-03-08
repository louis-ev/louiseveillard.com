<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">

	<div class="module--intro">
		<header class="module--into--textblock">
	  	<?php echo $page->text()->kirbytext() ?>
	  </div>
	</div>

	<?php snippet( "module--projetList"); ?>

  <div class="module--projet_short">
    <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
  		<article class="module--projet" data-visuel="zoomedIn">
  			<?php snippet( "projet--header", array( 'p' => $p)); ?>
  		</article>
  	<?php endforeach ?>
	</div>
</main>

<?php snippet('footer') ?>