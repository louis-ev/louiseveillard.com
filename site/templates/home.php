<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="pjax-container" class="main" role="main">
  <?= pjaxInfos( $page); ?>
	<div class="module--intro">
		<header class="module--into--textblock">
	  	<?php echo $page->text()->kirbytext() ?>
	  </div>
	</div>

</main>

<?php snippet('projetlistetvignettes'); ?>

<?php snippet('footer') ?>