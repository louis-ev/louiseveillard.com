<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="pjax-container" class="main" role="main">
  <?= pjaxInfos( $page); ?>
	<div class="module--intro">
		<header class="module--intro--textblock">
	  	<?php echo $page->text()->kirbytext() ?>
	  </header>
	</div>

</main>

<?php snippet('projetlistetvignettes'); ?>

<?php snippet('footer') ?>