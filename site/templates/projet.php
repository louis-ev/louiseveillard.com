<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">
	<article class="module--projet">
		<?= snippet( "projet--header", array( 'projet' => $page)); ?>
		<section class="module--projet--text">
			<?php echo $page->text()->kirbytext() ?>
		</section>

	</article>
</main>
<?php snippet('footer') ?>