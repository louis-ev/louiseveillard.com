<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">

	<article class="module--projet" data-type="<?= str::slug( $page->type()); ?>">
		<?= snippet( "projet--header", array( 'p' => $page)); ?>
		<section class="module--projet--text">
			<?php echo $page->text()->kirbytext() ?>
		</section>

	</article>
</main>
<?php snippet('footer') ?>