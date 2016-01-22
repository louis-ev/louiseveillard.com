<header class="module--projet--header">
	<?php echo $projet->header()->kirbytext() ?>
	<div class="module--projet--visuel">
		<?php
			if( $projet->imageCover()->isNotEmpty())
				echo thumb( $projet->imageCover()->toFile(), array(
					'width' 	=> 1800,
				));
		?>
	</div>
</header>
