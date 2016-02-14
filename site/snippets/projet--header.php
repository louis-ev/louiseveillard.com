<?php $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT); ?>
<?php $isProjetPage = $page->template() == 'projet'; ?>

<header class="module--projet--header" data-index="<?= $index ?>">


	<div class="module--projet--header--text">
    <h1>
  	<span class="module--projet--header--text--index"><?= $index; ?></span>
  	<?= $p->title()->text() ?>
    </h1>

    <hr>

    <div class="module--projet--header--text--colLeft">
    	<?= $p->header()->kirbytext() ?>
    </div>
    <div class="module--projet--header--text--colRight">
    	<?= $p->headercol2()->kirbytext() ?>
    </div>
	</div>

</header>

<?php
	if( $p->imageCover()->isNotEmpty()):
	  $image = $p->imageCover()->toFile();
?>
<a class="module--projet--visuel" href="<?= $image->url() ?>" target="_blank">
  <?php
		echo kirbytag(array(
		  'image'  				=> $image->filename(),
		  'class'					=> "image cover",
		  'thumbwidth' 		=> 1600,
		  'maxLinkedImageWidth'	=> 2000,
		  'outputlink'    => false,
		  'originalPage'  => $p
		));
  ?>
</a>
<?php
  endif;
?>

