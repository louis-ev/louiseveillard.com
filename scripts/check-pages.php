<?php

/**
 * Renders every page in every language and fails (exit code 1) when a page:
 * - throws an error,
 * - doesn't answer 200 (except the error page, 404),
 * - still contains an unconverted kirbytag, e.g. "(image: …)",
 * - contains a broken link marker from Kirby (debug mode).
 *
 * Usage: php scripts/check-pages.php   (run by the deploy workflow before uploading)
 */

$root = dirname(__DIR__);
require $root . '/kirby/bootstrap.php';

$tags = 'image|link|file|email|tel|vimeo|video|lienProjet|jour|toutesDescriptions|gist|date';
$failures = [];
$count = 0;

$kirby = new Kirby(['roots' => ['index' => $root]]);
$languages = $kirby->languages()->codes();
$ids = $kirby->site()->index(true)->filter(fn ($page) => $page->isDraft() === false)->keys();

foreach ($ids as $id) {
  foreach ($languages as $code) {
    $path = ($code === $kirby->defaultLanguage()->code() ? '' : $code . '/') . $id;

    // fresh instance per request, as the web server would do
    $kirby = new Kirby([
      'roots' => ['index' => $root],
      'options' => ['debug' => true],
    ]);

    try {
      $response = $kirby->render($path);
      $code = $response->code();
      $body = (string)$response->body();
    } catch (Throwable $e) {
      $failures[] = "/$path: " . get_class($e) . ' — ' . $e->getMessage();
      continue;
    }

    $count++;
    $expected = match (true) {
      str_ends_with($id, 'error') => 404,
      $id === 'projets' => 302, // redirects to the home page (config.php)
      default => 200,
    };

    if ($code !== $expected) {
      $failures[] = "/$path: HTTP $code";
    }

    if (preg_match('/\((' . $tags . '):\s*[^)\s]+/', strip_tags($body), $match) === 1) {
      $failures[] = "/$path: unconverted kirbytag \"" . $match[0] . '"';
    }

    if (str_contains($body, 'kirby-broken-link') === true) {
      $failures[] = "/$path: broken link";
    }
  }
}

echo "$count page(s) rendered\n";

if ($failures !== []) {
  echo count($failures) . " problem(s):\n- " . implode("\n- ", $failures) . "\n";
  exit(1);
}

echo "all good\n";
