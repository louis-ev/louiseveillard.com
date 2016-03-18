<?php

kirbytext::$tags['lienProjet'] = array(
	'html' => function($tag) {

    $markup = new Brick( 'span');
    $nomprojet = $tag->attr('lienProjet');

    if( !$nomprojet)
      return false;

		if(strpos($nomprojet,',') === false) {
      $markup = returnProjectIndexAsLink( $nomprojet);
    } else {
			$nomprojets = str::split(str_replace(' ', '', $nomprojet), ',');
			foreach( $nomprojets as $nomprojet):
        $markup .= returnProjectIndexAsLink( $nomprojet);
      endforeach;
    }

		return $markup;

	}
);


function returnProjectIndexAsLink( $nomprojet) {

	$projet = page('projets')->children()->visible()->findBy('uid', $nomprojet);


  if( !$projet)
    return '<small><strong>mauvais UID</strong></small>';

  echo $projet->title();

  $lienIndex = str_pad( $projet->num(), 2, '0', STR_PAD_LEFT);


  $lienProjet = new Brick('a');
  $lienProjet->addClass('lienProjet');
  $lienProjet->attr('href', $projet->url());
  $lienProjet->attr('data-rubrique', str::slug( $projet->type()));
  $lienProjet->append( $lienIndex);

  return $lienProjet;
}