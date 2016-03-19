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
c::get('optimumx', 1.6);

c::set('smartypants', true);
c::set('kirby.extension.videoext.video_tag', true);
c::set('kirby.extension.videoext.class', 'media-video lazyload');
c::set('kirby.extension.videoext.caption_class', 'media-video');
c::set('kirby.extension.videoext.controls', false);
c::set('kirby.extension.videoext.preload', 'none');
c::set('kirby.extension.videoext.loop', true);

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
