<?php

kirbytext::$tags['toutesDescriptions'] = array(
	'attr' => array(
	),
	'html' => function($tag) {


    ob_start();

    echo '<style>.module--projet--visuel {
        display: none !important;
    }

    header.module--projet--header {
        margin-top: 0 !important;
    }</style>';

    foreach(site()->children()->findByURI('projets')->children()->visible() as $p):
      $projectSlug = str::slug( $p->type());
      $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT);

      echo '<article class="module--projet module--projet_full">';
      	  echo snippet( "projet--header", array( 'p' => $p));

/*
      		<section class="module--projet--text">
      			<?php echo $p->text()->kirbytext() ?>
      		</section>
*/
      echo '</article>';

    endforeach;

    $_content = ob_get_contents();
    ob_end_clean();


		return $_content;

	}
);
