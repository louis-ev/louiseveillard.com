<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\File;
use Kirby\Cms\Html;
use Kirby\Cms\Page;
use Kirby\Cms\Response;
use Kirby\Text\KirbyTag;
use Kirby\Toolkit\Str;

/**
 * Returns the html callback of a core kirbytag, so that overridden tags can delegate to it
 */
function levCoreTag(string $type): Closure
{
  static $tags;
  $tags ??= require Kirby::instance()->root('kirby') . '/config/tags.php';
  return $tags[$type]['html'];
}

/**
 * Only the production domain is indexed by search engines (not a test folder
 * or subdomain)
 */
function levIsProduction(): bool
{
  $kirby = Kirby::instance();
  return $kirby->request()->url()->host() === $kirby->option('louiseveillard.site.productionHost');
}

/**
 * Appends the file modification time to local CSS/JS URLs, so they can be
 * cached for a long time (see .htaccess) and still update on each deploy
 */
function levVersioned(string $url): string
{
  // relative (`assets/css/main.css`) or absolute URL, site at the domain root
  $path = ltrim((string)parse_url($url, PHP_URL_PATH), '/');
  $file = Kirby::instance()->root('index') . '/' . $path;

  return is_file($file) ? $url . '?v=' . filemtime($file) : $url;
}

/**
 * Kirbytags output a single line: markdown never splits them (e.g. inside
 * list items) and the following line starts a new paragraph
 */
function levOneLine(string $html): string
{
  return preg_replace('/\s*\n\s*/', ' ', trim($html)) . "\n";
}

/**
 * Image figure, used by the `image` kirbytag and the project cover:
 * responsive <picture> in an aspect-ratio box (placeholder while loading)
 */
function levFigure(File $image, array $options = []): string
{
  $caption = $options['caption'] ?? null;
  $link = $options['link'] ?? true;
  $class = trim('media-image FigureImage ' . ($options['class'] ?? ''));

  $picture = snippet('picture', [
    'file' => $image,
    'width' => $options['width'] ?? 2400,
    'sizes' => $options['sizes'] ?? '(min-width: 66rem) 66rem, 100vw',
    'class' => 'FigureImage-item',
    'alt' => $options['alt'] ?? '',
    'lazy' => $options['lazy'] ?? true,
  ], true);

  $ratio = $image->width() && $image->height() ? $image->width() . ' / ' . $image->height() : null;
  $html = '<div class="image"><div class="FigureImage-lazy"' . ($ratio ? ' style="aspect-ratio: ' . $ratio . '"' : '') . '>' . $picture . '</div></div>';

  if ($link === true) {
    // animated gifs are linked as is, other images to a large thumb
    $href = $image->extension() === 'gif' ? $image->url() : $image->thumb(['width' => 1600, 'quality' => 95])->url();
    $html = '<a target="_blank" href="' . $href . '">' . $html . '</a>';
  }

  if ($caption) {
    $html .= '<figcaption class="legende FigureImage-caption">' . $caption . '</figcaption>';
  }

  return levOneLine('<figure class="' . $class . '">' . $html . '</figure>');
}

Kirby::plugin('louiseveillard/site', [
  'options' => [
    'productionHost' => 'louiseveillard.com',
  ],

  'components' => [
    'css' => fn (Kirby $kirby, string $url) => levVersioned($url),
    'js' => fn (Kirby $kirby, string $url) => levVersioned($url),
  ],

  'routes' => [
    [
      'pattern' => 'sitemap.xml',
      'action' => function () {
        $site = site();
        $pages = $site->children()->listed()->add(page('projets')?->children()->listed() ?? []);
        $pages = $pages->prepend($site->homePage()->id(), $site->homePage());

        return new Response(snippet('sitemap', ['pages' => $pages], true), 'application/xml');
      },
    ],
    [
      'pattern' => 'robots.txt',
      'action' => function () {
        $robots = levIsProduction()
          ? "User-agent: *\nAllow: /\n\nSitemap: " . url('sitemap.xml') . "\n"
          : "User-agent: *\nDisallow: /\n";

        return new Response($robots, 'text/plain');
      },
    ],
  ],

  'pageMethods' => [
    // <title>: page title first, the site name alone on the home page
    'metaTitle' => function (): string {
      $site = $this->site();
      return $this->isHomePage() ? $site->title()->value() : $this->title()->value() . ' — ' . $site->author()->value();
    },
    // meta description: project introduction, page text, or site description
    'metaDescription' => function (): string {
      if ($this->isHomePage() === false) {
        foreach (['header', 'text'] as $field) {
          $text = $this->content()->get($field);
          if ($text->isNotEmpty() === true) {
            // without the small caps label that starts some texts ("Informations")
            $html = preg_replace('#^\s*(<h3>.*?</h3>|<p class="titleCaps">.*?</p>)#s', '', (string)$text->kirbytext());
            // and without the (lienProjet: …) numbers, meaningless as text
            $html = preg_replace(['#\s*\(?\s*(<a class="lienProjet"[^>]*>.*?</a>\s*)+\)?#s', '#<style.*?</style>#s'], '', $html);
            $excerpt = Str::excerpt($html, 160);
            if ($excerpt !== '') return $excerpt;
          }
        }
      }
      return $this->site()->description()->value();
    },
    // two-digit project number, e.g. "03"
    'projetIndex' => function (): string {
      return $this->num() !== null ? str_pad((string)$this->num(), 2, '0', STR_PAD_LEFT) : '';
    },
    // project category slug, e.g. "outils-pedagogiques"
    'rubrique' => function (): string {
      return Str::slug($this->content()->get('type')->value() ?? '');
    },
  ],

  'hooks' => [
    // Kirby 2 `ssl` option: redirect http:// to https://
    'route:before' => function () {
      $kirby = kirby();

      if (
        $kirby->option('louiseveillard.ssl') !== true ||
        $kirby->request()->method() !== 'GET' ||
        PHP_SAPI === 'cli' || PHP_SAPI === 'cli-server'
      ) {
        return;
      }

      $https = $kirby->environment()->https()
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null) === 'https'
        || ($_SERVER['HTTP_X_FORWARDED_SSL'] ?? null) === 'on';

      if ($https === false && $kirby->environment()->isLocal() === false) {
        go('https://' . $kirby->environment()->host() . ($_SERVER['REQUEST_URI'] ?? '/'), 301);
      }
    },

    // a line with `---` closes the current project text section and opens
    // a new one (sections alternate between dark and light backgrounds)
    'kirbytext:before' => function (?string $text): string {
      return preg_replace('/^[ \t]*-{3,4}[ \t]*$/m', '</section><section class="module--projet--text">', $text ?? '');
    },
  ],

  'tags' => [
    // (image: name.jpg legende: … taille: colonne)
    'image' => [
      'attr' => ['legende', 'taille', 'alt', 'class'],
      'html' => function (KirbyTag $tag): string {
        $image = $tag->file($tag->value);

        if ($image === null || $image->type() !== 'image') {
          return '';
        }

        $column = $tag->taille !== null && Str::lower($tag->taille) === 'colonne';
        $caption = $tag->legende ? $tag->kirby()->kirbytext($tag->legende, ['parent' => $tag->parent()]) : null;
        $class = trim(($tag->taille ? 'largeur--' . ucfirst($tag->taille) : '') . ' ' . $tag->class);

        return levFigure($image, [
          'caption' => $caption,
          'class' => $class,
          'alt' => $tag->alt ?? ($tag->legende ? strip_tags($tag->legende) : ''),
          'sizes' => $column ? '(min-width: 44rem) 44rem, 100vw' : '(min-width: 66rem) 66rem, 100vw',
        ]);
      },
    ],

    // (video: mp4: a.mp4 webm: a.webm poster: a.jpg class: … caption: … autoplay: true)
    // Kirby 2 videoext tag; other values (e.g. a youtube URL) go to the core tag.
    // Autoplaying videos are muted and only play while visible (see main.js).
    'video' => [
      'attr' => ['mp4', 'webm', 'ogg', 'poster', 'class', 'caption', 'autoplay', 'loop', 'controls', 'muted', 'preload'],
      'html' => function (KirbyTag $tag): string {
        if (empty($tag->value) === false) {
          return levCoreTag('video')($tag);
        }

        $url = fn (?string $name) => $name ? ($tag->file($name)?->url() ?? $name) : null;
        $bool = fn ($value, bool $default) => $value === null ? $default : Str::toType($value, 'bool');

        $autoplay = $bool($tag->autoplay, false);
        $sources = '';

        foreach (['webm', 'mp4', 'ogg'] as $type) {
          if ($src = $url($tag->$type)) {
            $sources .= '<source src="' . Html::encode($src) . '" type="video/' . $type . '">';
          }
        }

        $video = Html::tag('video', [$sources], [
          'class' => $tag->class ?? 'media-video',
          'poster' => $url($tag->poster),
          'preload' => $autoplay ? 'none' : ($tag->preload ?? 'metadata'),
          'controls' => $bool($tag->controls, false),
          'loop' => $bool($tag->loop, true),
          'muted' => $autoplay || $bool($tag->muted, false),
          'playsinline' => $autoplay,
          'data-autoplay' => $autoplay ? 'true' : null,
        ]);

        if ($tag->caption) {
          $video = '<figure class="media-video">' . $video . '<figcaption class="caption-bottom">' . Html::encode($tag->caption) . '</figcaption></figure>';
        }

        return levOneLine($video);
      },
    ],

    // Kirby 2 legacy tag: lazy-loaded player, without Vimeo tracking cookies
    'vimeo' => [
      'attr' => ['caption'],
      'html' => function (KirbyTag $tag): string {
        $iframe = Html::vimeo($tag->value, ['dnt' => 1], [
          'loading' => 'lazy',
          'title' => $tag->caption ? strip_tags($tag->caption) : 'Vimeo',
        ]);

        if ($iframe === null) {
          return Html::a($tag->value);
        }

        $caption = $tag->caption ? '<figcaption>' . $tag->caption . '</figcaption>' : '';
        return levOneLine('<figure class="video">' . $iframe . $caption . '</figure>');
      },
    ],

    // Kirby 2 `popup: yes` attribute
    'link' => [
      'attr' => ['class', 'lang', 'popup', 'rel', 'role', 'target', 'title', 'text'],
      'html' => function (KirbyTag $tag): string {
        if (Str::toType($tag->popup, 'bool') === true) {
          $tag->target ??= '_blank';
          $tag->rel ??= 'noopener noreferrer';
        }

        return levCoreTag('link')($tag);
      },
    ],

    // (lienProjet: dodoc, panopticon): links to projects, shown as their number
    'lienprojet' => [
      'html' => function (KirbyTag $tag): string {
        $projets = page('projets')?->children()->listed();
        $links = [];

        foreach (Str::split($tag->value, ',') as $uid) {
          if ($projet = $projets?->find($uid)) {
            $links[] = Html::a($projet->url(), $projet->projetIndex(), [
              'class' => 'lienProjet',
              'title' => $projet->title()->value(),
              'data-rubrique' => $projet->rubrique(),
            ]);
          }
        }

        return implode('', $links);
      },
    ],

    // (jour: Bonjour nuit: Bonsoir): the night variant is shown by main.js
    'jour' => [
      'attr' => ['nuit'],
      'html' => function (KirbyTag $tag): string {
        return '<span class="js--isJour">' . $tag->value . '</span><span class="js--isNuit" hidden>' . $tag->nuit . '</span>';
      },
    ],

    // (toutesDescriptions: oui): headers of every project, without their cover
    'toutesdescriptions' => [
      'html' => function (KirbyTag $tag): string {
        $html = '<style>.module--projet--visuel { display: none !important; } .module--projet--header { margin-top: 0 !important; }</style>';

        foreach (page('projets')?->children()->listed() ?? [] as $p) {
          $html .= '<article class="module--projet module--projet_full" data-type="' . $p->rubrique() . '">'
            . snippet('projet-header', ['p' => $p], true)
            . '</article>';
        }

        // indented lines would become markdown code blocks
        return levOneLine($html);
      },
    ],
  ],
]);
