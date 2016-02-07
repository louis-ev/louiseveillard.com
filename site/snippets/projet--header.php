<header class="module--projet--header">

	<div class="module--projet--header--text">
  	<?php echo $projet->header()->kirbytext() ?>
  	<h3><?php echo $projet->annee()->text() ?></h3>
	</div>
	<div class="module--projet--header--visuel">
		<?php
			if( $projet->imageCover()->isNotEmpty())
				echo thumb( $projet->imageCover()->toFile(), array(
					'width' 	=> 1800,
				));
		?>
	</div>
</header>
