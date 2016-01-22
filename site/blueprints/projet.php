<?php if(!defined('KIRBY')) exit ?>

title: Projets
pages: true
files: true
	sortable: true
fields:
  title:
    label: Title
    type:  text
  header:
    label: Header
    type:  textarea
  text:
    label: Text
    type:  textarea

  cover:
  	label: Aperçu
  	type: headline
  imageCover:
  	label: Couverture
  	type: selector
  	help: Image ou vidéo

