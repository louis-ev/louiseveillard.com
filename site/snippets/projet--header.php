<?php $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT); ?>
<?php $isProjetPage = $page->template() == 'projet'; ?>

<header class="module--projet--header <?= e( $isProjetPage == 1, 'is--collapsed', ''); ?>" data-index="<?= $index ?>">


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


  <div class="module--projet--header--visuel">
  	<?php
  		if( $p->imageCover()->isNotEmpty())
  			echo thumb( $p->imageCover()->toFile(), array(
  				'width' 	=> 1800,
  			));
  	?>
  </div>
</header>
