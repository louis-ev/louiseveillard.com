<?php

/**
 * Outils Plugin
 */

// permet d'ajouter une url à la liste des liens (en tenant compte
// des paramètres d'url) visités durant la php_session()
// (appelé dans header.php)
function add_to_visited_links($url){

	$url = rtrim( $url, '/');
	$visited_links = s::get('visited_links');
	if(!$visited_links) $visited_links = array();

	array_push($visited_links, $url);

	s::set('visited_links', array_unique($visited_links));
}

// permet de tester si l'url d'une page a déjà été visitée
// durant la php_session()
// a utiliser avec le shortcut d'echo ternaire kirby
// e(boolean, $a, $b);
function is_visited($url){
//   var_dump( s::get('visited_links'));
	return (in_array(rtrim( $url, '/'), s::get('visited_links')));
}

function pjaxInfos($p) {

  $response = array();
  $response["template"] = $p->template();
  $response["intended-template"] = $p->intendedTemplate();
  $response["rubrique"] = $p->type()->exists() ? str::slug( $p->type()): '';
  return '<script>var pjaxInfos = ' . json_encode( $response) . '</script>';
}