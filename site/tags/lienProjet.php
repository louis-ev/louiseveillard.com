<?php

kirbytext::$tags['lienProjet'] = array(
	'html' => function($tag) {

    $markup = '';
    $nomprojet = $tag->attr('lienProjet');

    if( !$nomprojet)
      return false;

		if(strpos($nomprojet,',') === false) {

      $projet = page('projets')->children()->visible()->findBy('uid', $nomprojet);

      if($projet == null) return;
      $markup = returnProjectIndexAsLink( $projet);

    } else {
			$nomprojets = str::split(str_replace(' ', '', $nomprojet), ',');

			foreach( $nomprojets as $nomprojet):
			  $projet   = page('projets')->children()->visible()->findBy('uid', $nomprojet);
        if($projet == null) continue;
        $markup .= returnProjectIndexAsLink( $projet)->toString();
      endforeach;
    }

    // suppression du dernier espace, après le dernier index projet
    $markup = trim( $markup);

		return $markup;

	}
);


function returnProjectIndexAsLink( $projet) {

  if( !$projet)
    return '<small><strong>mauvais UID</strong></small>';

  $num = str_pad( $projet->num(), 2, '0', STR_PAD_LEFT);

  $lienProjet = new Brick('a');
  $lienProjet->addClass('lienProjet');
  $lienProjet->attr('href', $projet->url());
  $lienProjet->attr('title', $projet->title());
  $lienProjet->attr('data-rubrique', str::slug( $projet->type()));
  $lienProjet->append( $num);

  return $lienProjet;
}