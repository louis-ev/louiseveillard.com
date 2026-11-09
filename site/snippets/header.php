<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?? 'fr' ?>">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <?php $title = $site->title() . ' | ' . $page->title() ?>
  <title><?= html($title) ?></title>

  <meta name="description" content="<?= $site->description()->html() ?>">
  <meta name="keywords" content="<?= $site->keywords()->html() ?>">
  <meta name="author" content="<?= $site->author()->html() ?>">
  <link rel="canonical" href="<?= $page->url() ?>">

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="/manifest.json">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#03b8f4">
  <meta name="theme-color" content="#03b8f4">

  <script>document.documentElement.classList.add('js');</script>

  <?= css([
    'assets/css/fonts.css',
    'assets/css/variables.css',
    'assets/css/base.css',
    'assets/css/main.css',
  ]) ?>

  <?= js('assets/js/main.js', ['defer' => true]) ?>

  <meta property="og:site_name" content="<?= $site->title()->html() ?>">
  <meta property="og:title" content="<?= html($title) ?>">
  <meta property="og:description" content="<?= $site->description()->html() ?>">
  <meta property="og:url" content="<?= $page->url() ?>">
  <meta property="og:image" content="<?= $site->ogimage()->html() ?>">
  <meta property="og:type" content="website">

  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="<?= html($title) ?>">
  <meta name="twitter:description" content="<?= $site->description()->html() ?>">
  <meta name="twitter:image" content="<?= $site->ogimage()->html() ?>">

</head>
<body
  data-template="<?= $page->template() ?>"
  data-intended-template="<?= $page->intendedTemplate() ?>"
  data-rubrique="<?= $page->rubrique() ?>"
  data-index="<?= $page->intendedTemplate()->name() === 'projet' ? $page->projetIndex() : '' ?>"
  data-visuel="">
  <script>
    // fade-in on load (removed by main.js)
    document.body.classList.add('is--loading');
  </script>
