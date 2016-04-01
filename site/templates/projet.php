<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="pjax-container" class="main" role="main">
  <?= pjaxInfos( $page); ?>
	<article class="module--projet module--projet_full" data-type="<?= str::slug( $page->type()); ?>">
		<?= snippet( "projet--header", array( 'p' => $page)); ?>
		<section class="module--projet--text">
			<?php echo $page->text()->kirbytext() ?>
		</section>
	</article>

</main>

<?php snippet('projetlistetvignettes'); ?>

<?php snippet('footer') ?>