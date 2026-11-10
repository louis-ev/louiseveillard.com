<?php
/**
 * sitemap.xml (route in site/plugins/louiseveillard); no <lastmod>: file
 * dates on the server are the upload dates, not the content dates
 *
 * @var Kirby\Cms\Pages $pages
 */
?>
<?= '<?xml version="1.0" encoding="utf-8"?>' . "\n" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $p): ?>
  <url>
    <loc><?= html($p->url()) ?></loc>
  </url>
<?php endforeach ?>
</urlset>
