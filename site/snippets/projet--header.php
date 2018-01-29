<?php $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT); ?>
<?php $isProjetPage = $page->template() == 'projet'; ?>

<?php
	if( $p->imageCover()->isNotEmpty()):
	  $image = $p->imageCover()->toFile();
?>
<a class="module--projet--visuel" href="<?= $image->url() ?>" target="_blank" tabindex="-1">
  <?php
    echo kirbytag(array(
		  'image'  				        => $image->filename(),
		  'thumbwidth' 		        => 2400,
		  'originalPage'          => $p,
		  'outputlink'            => false,
		  'optimumx'              => 1.8,
		  'maxLinkedImageWidth'   => 2400,
		  'return_srcset'         => false
		));

?>

</a>
<?php
  endif;
?>

<header class="module--projet--header" data-index="<?= $index ?>">

	<div class="module--projet--header--text">
  	<span class="module--projet--header--text--index"><?= $index ?></span>
    <h1 class="module--projet--header--text--name">
      <?= $p->title()->html() ?>
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

