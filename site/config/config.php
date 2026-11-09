<?php

/*

---------------------------------------
Kirby Configuration
---------------------------------------

Local overrides go in config.localhost.php.
The Kirby license is activated from the Panel (site/config/.license).

*/

date_default_timezone_set('Europe/Paris');

// written by the deploy workflow; the page cache is keyed by it so that
// every deploy (which may change content/ over FTP) starts with a fresh cache
$deploy = trim((string)@file_get_contents(__DIR__ . '/.deploy'));

return [
  'debug' => false,

  'cache' => [
    'pages' => [
      'active' => $deploy !== '',
      'prefix' => 'pages/' . $deploy,
    ],
  ],

  // single language, but content files are named *.fr.txt
  'languages' => true,

  // content still references files by filename (no UUIDs in .txt files)
  'content' => [
    'uuid' => false,
  ],

  'smartypants' => true,

  'thumbs' => [
    'quality' => 75,
  ],

  // redirects http:// to https:// (Kirby 2 `ssl` option)
  'louiseveillard.ssl' => true,

  // the project list is on the home page
  'routes' => [
    [
      'pattern' => 'projets',
      'action' => fn () => Kirby\Cms\Response::redirect(site()->url(), 302),
    ],
  ],

  // Panel disabled online for now: content is edited in the repo
  // (enabled locally in config.localhost.php)
  'panel' => false,
];
