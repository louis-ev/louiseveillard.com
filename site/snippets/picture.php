<?php
/**
 * Responsive image: WebP + original format srcsets,
 * with width/height to avoid layout shifts.
 *
 * @var Kirby\Cms\File $file
 * @var int $width  largest rendered width (also the `src` fallback)
 * @var string $sizes
 * @var string|null $class
 * @var string|null $alt
 * @var bool|null $lazy  false for images visible on load
 */

$class ??= null;
$alt ??= '';
$lazy ??= true;
$loading = $lazy ? ' loading="lazy" decoding="async"' : ' fetchpriority="high"';

// animated gifs and svgs are served as is
if (in_array($file->extension(), ['gif', 'svg']) === true):
  ?><img src="<?= $file->url() ?>" alt="<?= html($alt) ?>"<?= r($class, ' class="' . $class . '"') ?><?= $file->width() ? ' width="' . $file->width() . '" height="' . $file->height() . '"' : '' ?><?= $loading ?>><?php
  return;
endif;

$ratio = $file->height() / max($file->width(), 1);
$width = min($width, $file->width());

$widths = array_filter([300, 600, 1200, 1600, 2000], fn ($w) => $w < $width);
$widths[] = $width;

$srcset = fn (?string $format = null) => implode(', ', array_map(
  fn ($w) => $file->thumb(array_filter(['width' => $w, 'format' => $format]))->url() . ' ' . $w . 'w',
  $widths
));
?>
<picture>
  <source type="image/webp" srcset="<?= $srcset('webp') ?>" sizes="<?= $sizes ?>">
  <img
    src="<?= $file->thumb(['width' => $width])->url() ?>"
    srcset="<?= $srcset() ?>"
    sizes="<?= $sizes ?>"
    width="<?= $width ?>"
    height="<?= (int)round($width * $ratio) ?>"
    alt="<?= html($alt) ?>"<?= r($class, ' class="' . $class . '"') ?><?= $loading ?>>
</picture>
