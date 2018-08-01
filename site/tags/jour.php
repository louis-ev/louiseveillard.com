<?php

kirbytext::$tags['jour'] = array(
	'attr' => array(
		'nuit'
	),
	'html' => function($tag) {
		$jour = $tag->attr('jour');
		$nuit = $tag->attr('nuit');

    ob_start();

    echo '<span class="js--isJour">' . $jour . '</span>';
    echo '<span class="js--isNuit" style="display: none;">' . $nuit . '</span>';

    $_content = ob_get_contents();
    ob_end_clean();


		return $_content;

	}
);
