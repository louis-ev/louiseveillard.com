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

<!--   <div class="module--projet--visuel--inside lazyload" data-sizes="auto" data-bgset="//localhost:3002/louiseveillard.com-v2/thumbs/point-a-point_puces-typo_sorties-2-25f4b9838e0bba92c6706d1084933f91.jpg 300w, //localhost:3002/louiseveillard.com-v2/thumbs/point-a-point_puces-typo_sorties-2-2df06d4e567df22bef064fca78ee896c.jpg 600w, //localhost:3002/louiseveillard.com-v2/thumbs/point-a-point_puces-typo_sorties-2-ec4ababbda4ed1f2a9d467cce9251fe6.jpg 1200w, //localhost:3002/louiseveillard.com-v2/thumbs/point-a-point_puces-typo_sorties-2-61240d108f3dae67e0a893a1ad94057f.jpg 1600w, //localhost:3002/louiseveillard.com-v2/thumbs/point-a-point_puces-typo_sorties-2-4a28150fb1318aaf4060c0b4455826be.jpg 2800w"></div> -->

  <?php
		echo kirbytag(array(
		  'image'  				=> $image->filename(),
		  'class'					=> "image cover",
		  'thumbwidth' 		=> 2800,
		  'maxLinkedImageWidth'	=> 2400,
		  'outputlink'    => false,
		  'originalPage'  => $p
		));
  ?>
</a>
<?php
  endif;
?>

