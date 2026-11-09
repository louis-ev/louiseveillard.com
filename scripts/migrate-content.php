<?php

/**
 * One-shot migration of Kirby 2 content to Kirby 5. Idempotent.
 *
 * 1. Files starting with `_` are ignored by Kirby 5: they lose the underscore
 *    (with their metadata files) and references in texts are updated.
 * 2. Kirby 2 "selector" field stored filenames as a comma-separated string
 *    (`Imagecover: a.jpg`); the Kirby 5 `files` field expects a YAML list.
 * 3. Blockquotes separated by an empty line are merged, as Kirby 2 rendered them.
 * 4. Hard links to `/content/2-infos/…` in texts follow the renamed folders.
 * 5. Numbered folders use `_` since Kirby 3 (`2-infos` → `2_infos`).
 *
 * Usage: php scripts/migrate-content.php [path/to/content]
 */

$root = realpath($argv[1] ?? __DIR__ . '/../content');
$fields = ['Imagecover'];

if ($root === false || is_dir($root) === false) {
  fwrite(STDERR, "Content folder not found\n");
  exit(1);
}

function relative(string $path): string
{
  global $root;
  return substr($path, strlen($root) + 1);
}

function contentFiles(string $root): array
{
  $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
  return iterator_to_array($files, false);
}

// 1. files starting with an underscore
$unprefixed = [];

foreach (contentFiles($root) as $file) {
  $name = $file->getFilename();

  if ($file->isFile() === false || str_starts_with($name, '_') === false || $file->getExtension() === 'txt') {
    continue;
  }

  $dir = $file->getPath();
  $target = ltrim($name, '_');

  if (file_exists($dir . '/' . $target) === true) {
    fwrite(STDERR, 'skipped ' . relative($file->getPathname()) . " ($target already exists)\n");
    continue;
  }

  // the file and its metadata (`_a.jpg.fr.txt`, `_a.jpg.en.txt`…)
  foreach (glob($dir . '/' . $name . '*') as $path) {
    rename($path, $dir . '/' . $target . substr(basename($path), strlen($name)));
  }

  $unprefixed[$name] = $target;
  echo 'renamed ' . relative($file->getPathname()) . "\n";
}

// 2–4. texts
$fieldPattern = '/^(' . implode('|', $fields) . '):[ \t]*(.*?)[ \t]*$(?=\s*(?:\n----|\z))/ims';
$changed = 0;

foreach (contentFiles($root) as $file) {
  if ($file->getExtension() !== 'txt') {
    continue;
  }

  $path = $file->getPathname();
  $content = file_get_contents($path);
  $migrated = $content;

  foreach ($unprefixed as $name => $target) {
    $migrated = preg_replace('/(?<![\w.-])' . preg_quote($name, '/') . '/', $target, $migrated);
  }

  $migrated = preg_replace_callback($fieldPattern, function ($match) {
    $value = trim($match[2]);

    // empty or already a YAML list
    if ($value === '' || str_starts_with($value, '-')) {
      return $match[0];
    }

    $filenames = array_filter(array_map('trim', explode(',', $value)), 'strlen');
    $list = implode("\n", array_map(fn ($name) => '- ' . $name, $filenames));

    return $match[1] . ":\n\n" . $list;
  }, $migrated);

  $migrated = preg_replace('/^(>.*)\n\n(?=>)/m', "$1\n>\n", $migrated);

  // only folder segments (followed by a slash) are renamed
  $migrated = preg_replace_callback(
    '#/content/[^\s)"\'\]>]+#',
    fn ($match) => preg_replace('#/(\d+)-(?=[^/]*/)#', '/$1_', $match[0]),
    $migrated
  );

  if ($migrated !== $content) {
    file_put_contents($path, $migrated);
    $changed++;
    echo 'migrated ' . relative($path) . "\n";
  }
}

// 5. folders, deepest first so parent paths stay valid
$dirs = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
  RecursiveIteratorIterator::CHILD_FIRST
);
$renamed = 0;

foreach ($dirs as $dir) {
  if ($dir->isDir() === false || preg_match('/^(\d+)-(.+)$/', $dir->getFilename(), $match) !== 1) {
    continue;
  }

  rename($dir->getPathname(), $dir->getPath() . '/' . $match[1] . '_' . $match[2]);
  $renamed++;
  echo 'renamed ' . relative($dir->getPathname()) . "\n";
}

echo count($unprefixed) . " file(s) unprefixed, $changed text(s) migrated, $renamed folder(s) renamed\n";
