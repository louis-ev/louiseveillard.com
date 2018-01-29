<?php
/**
 * Figure
 * ----
 * Custom (multi) figure tag
 *
 * What it does:
 * Generates an image wrapped in a <figure> tag, with a lot of additional
 * options. Among other things multiple images within one figure tag, the use
 * of a 'relative' width (i.e. 1of3 or 1of4) for responsive images.
 *
 * Usage:
 * 1) (figure: myimage.jpg width: 1of3)
 * 2) (figure: myimage.jpg width: 1of3 legende: Nice figure legende!)
 * 3) (figure: myimage.jpg griditem: true legende: Single image in a multifigure grid)
 * 4) (figure: myimage.jpg width: 2of3 height: 200 crop: true legende: Nice figure legende!)
 * 5) (figure: myimage.jpg width: width: 2of3 align: center)
 * 6) (figure: myimage1.jpg | myimage2.jpg, myimage3.jpg width: 1of3, 1of3, 1of3 break: medium gutter: percentage)
 *
 * Example page:
 * http://altair.studiodumbar.com/images
 */

kirbytext::$tags['image'] = array(
	'attr' => array(
		// Basics
		'legende',
		// Cropping and quality
		'crop',
		'cropratio',
		'upscale',
		'quality',
		// CSS class setting
		'break',
		'gutter',
		'align',
		'griditem',
		// Single figure specific
		'height',
		'alt',
		'taille',
		'class',
		'originalPage',
		'outputlink',
		'nofigure',
		'multisizes',
		'thumbwidth',
		'maxLinkedImageWidth',
		'return_srcset',
		'optimumx',
	),
	'html' => function($tag) {

		$images = $tag->attr('image');

		// Check if the figure has multiple images to output, check for comma
		if(strpos($images,',') === false) {
			$is_multifigure = false;
			// Set the one images as the first in an images array
			$images = array($images);
		}
		else {
			$is_multifigure = true;
			// Set all images to the array
			$images = str::split(str_replace(' ', '', $images), ',');
		}

		// Check if there are images passed to the array
		if(empty($images)) return false;


		// Build array of image objects
		foreach($images as $img) {
			$imgObj = $tag->file($img);
			if($imgObj) $imageresult[] = $imgObj;
		}

		if( empty( $imgObj)) {
			// aucune image match les images associées d'un article. Appel depuis template ?
			//echo "plop";
			//echo $tag->attr('originalPage');
			foreach($images as $img) {

				$imgObj = $tag->attr('originalPage')->file($img);
				//echo "imgObj " . $imgObj;
/*
				echo "img = " . $img;
				echo "   ///   ";
				echo "imgOBJ = " . $imgObj;
*/
				if($imgObj) {
					$imageresult[] = $imgObj;
				}

			}
		}

		// Check if array of images has real items (after building objects)
		if(empty($imageresult)) return false;

		// set variables for both single and multi figures
		$upscale    = $tag->attr('upscale');
		$quality    = $tag->attr('quality', c::get('thumbs.quality', 92));
		$caption    = $tag->attr('legende');
		$break      = $tag->attr('break', c::get('figureimage.break', 'small'));
		$gutter     = $tag->attr('gutter', c::get('figureimage.gutter', 'default'));
		$offset     = $tag->attr('offset');
		$align      = $tag->attr('align');
		$griditem   = $tag->attr('griditem');
		$alt        = $tag->attr('alt');
		$crop       = $tag->attr('crop');
		$cropratio  = $tag->attr('cropratio');
		$width      = $tag->attr('width');
		$height     = $tag->attr('height');
		$taille 		= $tag->attr('taille');
		$class 			= $tag->attr('class');
		$outputlink = $tag->attr('outputlink');
		$optimumx = $tag->attr('optimumx', c::get('optimumx', 1.6));
		$maxLinkedImageWidth = $tag->attr('maxLinkedImageWidth');
		$return_srcset = $tag->attr('return_srcset');
		$nofigure = $tag->attr('nofigure');
		$thumbwidth = $tag->attr('thumbwidth');
		$multisizes = $tag->attr('multisizes');
		$lowResPreview = $tag->attr('lowrespreview');

		// Get width variable(s) of image(s)

		// Set classes used in layout grid
		if(count($imageresult) > 1 || $tag->attr('gutter') || isset($griditem)) {
			$gridclass = ' Grid Grid--withGutter' . (($gutter == 'percentage') ? 'Percentage' : '');
			$gridcellclass = 'Grid-cell ';
		}
		else {
			$gridclass = '';
			$gridcellclass = '';
		}

		// Set break class used in layout grid
		if(count($imageresult) > 1) {
			$breakclass = ' Grid--breakFrom'.ucfirst($break);
		}
		else {
			$breakclass = '';
		}

		// Set possible align classes
		if(isset($align)) {
			$alignclass = ' FigureImage--align'.ucfirst($align); // Add capitalized align class (IE: Grid--alignCenter)
		}
		else {
			$alignclass = '';
		}

		if(isset( $taille)) {
			$tailleclass = ' largeur--'.ucfirst($taille);
		} else {
			$tailleclass = '';
		}

		if( !isset( $outputlink)) {
			$outputlink = true;
			if( !isset( $maxLinkedImageWidth)) {
				$maxLinkedImageWidth = c::get('maxLinkedImageWidth',false);
			}
		}

		if( !isset( $return_srcset)) {
			$return_srcset = false;
		}

		if( !isset( $nofigure)) {
			$nofigure = false;
		}
		if( !isset( $multisizes)) {
			$multisizes = c::get('multisizes', false);
		}
		if( !isset( $lowResPreview)) {
			$lowResPreview = c::get('lowResPreview', false);
		}

		// If feed/rss page, lazyload is always disable
		if(kirby()->request()->path()->last() == 'feed') {
			$lazyload = false;
			$feed = true;
		}
		// Else lazyload variable is set in config
		else {
			$lazyload = c::get('lazyloadimages', false);
			$feed = false;
		}

		if( $nofigure == false) {
			$figure = new Brick('figure');
		} else {
			$figure = new Brick('div');
		}

		$figure->addClass('media-image');

		if($feed != true) {
			$figure->addClass('FigureImage' . $gridclass . $breakclass . $alignclass . $tailleclass . $class);
		}

		// Create markup for every image
		$i = 0;
		foreach($imageresult as $image) {

			// If the crop variable is explicitly set to 'false' string *and*
			// no cropratio is set, the crop variable is always set to false
			if($crop == 'false' && !isset($cropratio)) {
				$crop = false;
			}

			// si override de la taille max du thumb
			if( !isset( $thumbwidth)) {
				// Set thumb width for feed
				if($feed == true) {
					$thumbwidth = c::get('thumbs.width.feed', 1200);
				}
				else {
  				  $thumbwidth = $image->width();
				}
			}

			// hack crado : désactivation du width
			$widths = null;

			// When a cropratio is set, calculate the ratio based height
			if(isset($cropratio)) {
				// If cropratio is a fraction string (e.g. 1/2), convert to decimal
				if(strpos($cropratio, '/') !== false) {
					list($numerator, $denominator) = str::split($cropratio, '/');
					$cropratio = $numerator / $denominator;
				}
				// Calculate new thumb height based on cropratio
				$thumbheight = round($thumbwidth * $cropratio);
				// If a cropratio is set, the crop variable is always set to true
				$crop = true;
				// Manual defined (crop)ratio
				$ratio = $cropratio;
			}
			else {
				// Intrinsic image's ratio
				$ratio = 1 / $image->ratio();
				// Max. height of image
				$thumbheight = round($thumbwidth * $ratio);
			}

			// Percentage-padding to set image aspect ratio (prevents reflow on image load)
			$percentage_padding = floor( round($ratio * 100, 2) * 100) / 100;

			$lazydiv = new Brick('div');
			$lazydiv->addClass('FigureImage-lazy lazyload');
			$lazydiv->attr('style', 'padding-bottom: ' . number_format($percentage_padding, 2, '.', '') . '%;');

			$class = 'FigureImage-item';
  			$placeholderSRC = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

			if($image->extension() == 'gif') {
			  $outputlink = 1;

				$imagethumb = html::img( $placeholderSRC,array(
					'data-src'  => $image->url(),
					'data-sizes' =>	'auto',
					'class'     => $class . ' lazyload',
					'alt'       => html($alt),
          'data-parent-fit' => "cover"
        ));

  				$noscript = '<noscript><img src="'. $image->url() .'" class="'. $class .'" width="'. $image->width() .'" height="'. $image->height() .'" alt="'. html($alt) .'"/></noscript>';
			} else
			{
    			$thumburl = thumb($image,array(
    				'width'   => $thumbwidth,
    				'quality' => $quality,
    				'crop'    => $crop,
    			), false);
  				$ms_datasrcset = "";
  				if( $thumbwidth > 300) {
  					$ms_datasrcset .= generate_thumb( $image, $quality, $crop, 300);
  				}

  				if( $thumbwidth > 600) {
  					$ms_datasrcset .= generate_thumb( $image, $quality, $crop, 600);
  				}

  				if( $thumbwidth > 1200) {
  					$ms_datasrcset .= generate_thumb( $image, $quality, $crop, 1200);
  				}

  				if( $thumbwidth > 1600) {
  					$ms_datasrcset .= generate_thumb( $image, $quality, $crop, 1600);
  				}

  				$ms_datasrcset .= generate_thumb( $image, $quality, $crop, $thumbwidth);

        if($return_srcset)
    			  return $ms_datasrcset;

				$imagethumb = html::img( $placeholderSRC,array(
					'data-src'  => $thumburl,
					'data-sizes' =>	"auto",
					'data-srcset'  => $ms_datasrcset,
          'data-optimumx' => str_replace(',', '.', $optimumx),
					'class'     => $class . ' lazyload',
					'alt'       => html($alt),
          'data-parent-fit' => "cover"
        ));
  				$noscript = '<noscript><img src="'. $thumburl .'" class="'. $class .'" width="'. $thumbwidth .'" height="'. $thumbheight .'" alt="'. html($alt) .'"/></noscript>';

    		}

  			if($outputlink == 1 && $maxLinkedImageWidth !== false && $image->extension() !== 'gif') {
  				$largeImage = thumb($image,array(
  					'width'   => $maxLinkedImageWidth,
  					'quality' => 95,
  				));
  			} else {
  				$largeImage = $image;
  			}

			$lazydiv->append($imagethumb);
			$lazydiv->append($noscript);

			// si on veut un <a> autour de l'image
			if($outputlink == 1):
				$figure->append('<a target="_blank" href="' . $largeImage->url() . '" data-size="' . $largeImage->width() . 'x' . $largeImage->height() . '"><div class="image">' . $lazydiv . '</div></a>');
			else:
				$figure->append('<div class="image">' . $lazydiv . '</div>');
			endif;

			$i++;
		}

		// Add caption
		if(isset($caption) && strlen( $caption) > 0) {
			// Also add break class to figcaption if alignment is set to image
			if(count($widths) > 0 && isset($align)) {
				$figure->append('<figcaption class="legende FigureImage-caption u-size' . $width . '--' . $break  . '">' . kirbytext($caption) . '</figcaption>');
			}
			else {
				$figure->append('<figcaption class="legende FigureImage-caption">' . kirbytext($caption) . '</figcaption>');
			}
		}


		return $figure;

	}
);


function generate_thumb( $image, $quality, $crop, $srcsetimgwidth) {

	$thisimageurl = thumb( $image, array(
		'width'   => $srcsetimgwidth,
		'quality' => $quality,
		'crop'    => $crop,
	), false);
	return $thisimageurl . " " . $srcsetimgwidth . "w, ";

};


?>
