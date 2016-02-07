<?php

kirbytext::$tags['projetBG'] = array(
	'attr' => array(
		'couleur'
		),
	'html' => function($tag) {
		$couleur = $tag->attr('couleur');

		return '</div><div>';

	}
);
