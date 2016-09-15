<?php

/*

---------------------------------------
License Setup
---------------------------------------

Please add your license key, which you've received
via email after purchasing Kirby on http://getkirby.com/buy

It is not permitted to run a public website without a
valid license key. Please read the End User License Agreement
for more information: http://getkirby.com/license

*/

c::set('license', 'put your license key here');

/*

---------------------------------------
Kirby Configuration
---------------------------------------

By default you don't have to configure anything to
make Kirby work. For more fine-grained configuration
of the system, please check out http://getkirby.com/docs/advanced/options

*/

c::set('environment', 'production');
c::set('multisizes', true);
c::set('lazyloadimages', true);
c::set('optimumx', 1.6);
c::set('thumbsize', 1600);
c::set('maxLinkedImageWidth', 1600);

c::set('smartypants', true);
c::set('smartypants.doublequote.open', '&#8220;');                              // Openning smart double-quotes.
c::set('smartypants.doublequote.close', '&#8221;');                             // Closing smart double-quotes.
c::get('smartypants.space.frenchquote', '&#160;');                              // Space inside french quotes. "Voici la «_chose_» qui m'a attaqué."

c::set('kirby.extension.videoext.video_tag', true);
c::set('kirby.extension.videoext.class', 'media-video lazyload');
c::set('kirby.extension.videoext.caption_class', 'media-video');
c::set('kirby.extension.videoext.controls', false);
c::set('kirby.extension.videoext.preload', 'none');
c::set('kirby.extension.videoext.autoplay', false);
c::set('kirby.extension.videoext.loop', true);

c::set('smartypants',true);

c::set('languages', array(
  array(
    'code'    => 'fr',
    'name'    => 'Français',
    'default' => true,
    'locale'  => 'fr_FR',
    'url'     => '/',
  ),
/*
  array(
    'code'    => 'en',
    'name'    => 'English',
    'locale'  => 'en_US',
    'url'     => '/en',
  ),
*/
));

c::set('routes', array(
  array(
    'pattern' => 'projets',
    'action'  => function() {
      go('home');
    }
  )
));
