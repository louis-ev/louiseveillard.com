<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?? 'fr' ?>">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <?php
    $title = $page->metaTitle();
    $description = $page->metaDescription();

    // sharing image: the project cover, or content/ogimage.png
    $cover = $page->content()->get('imageCover')->toFile();
    if ($cover && $cover->extension() !== 'gif') {
      $ogimage = $cover->thumb(['width' => 1200, 'format' => 'jpg'])->url();
    } else {
      $ogimage = $cover?->url() ?? $site->image('ogimage.png')?->url() ?? $site->ogimage()->value();
    }
  ?>
  <title><?= html($title) ?></title>

  <meta name="description" content="<?= html($description) ?>">
  <meta name="author" content="<?= $site->author()->html() ?>">
  <link rel="canonical" href="<?= $page->url() ?>">
  <?php if (levIsProduction() === false): ?>
  <meta name="robots" content="noindex, nofollow">
  <?php endif ?>

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
  <meta property="og:description" content="<?= html($description) ?>">
  <meta property="og:url" content="<?= $page->url() ?>">
  <meta property="og:image" content="<?= html($ogimage) ?>">
  <meta property="og:type" content="<?= $page->isHomePage() ? 'website' : 'article' ?>">
  <meta property="og:locale" content="fr_FR">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= html($title) ?>">
  <meta name="twitter:description" content="<?= html($description) ?>">
  <meta name="twitter:image" content="<?= html($ogimage) ?>">

</head>
<body
  data-template="<?= $page->template() ?>"
  data-intended-template="<?= $page->intendedTemplate() ?>"
  data-rubrique="<?= $page->rubrique() ?>"
  data-index="<?= $page->intendedTemplate()->name() === 'projet' ? $page->projetIndex() : '' ?>"
  data-visuel="">
  <script>
    // fade-in on load (removed by main.js); never stays hidden if main.js fails
    document.body.classList.add('is--loading');
    setTimeout(function () { document.body.classList.remove('is--loading'); }, 3000);
  </script>
