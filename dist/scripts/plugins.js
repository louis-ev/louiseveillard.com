// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

/**
 * jQuery Picture
 * http://jquerypicture.com
 * http://github.com/Abban/jQuery-Picture
 *
 * May 2012
 *
 * @version 0.9
 * @author Abban Dunne http://abandon.ie
 * @license MIT
 *
 * jQuery Picture is a plugin to add support for responsive images to your layouts.
 * It supports both figure elements with some custom data attributes and the new
 * proposed picture format. This plugin will be made redundant when the format is
 * approved and implemented by browsers. Lets hope that happens soon. In the meantime
 * this plugin will be kept up to date with latest developments.
 *
 */

 /*
 	Function forceSmall added by Louis Eveillard for louiseveillard.com
 	When true, always load the smallest image. In combination with waypoint.js, larger images are only loaded when the project is in view
 	More info : hello@louiseveillard.com
 */

(function($){

	$.fn.picture = function(args){

		var defaults = {

			container : null,
			ignorePixelRatio: false,
			useLarger: false,
			insertElement: '>a',
			inlineDimensions: false,
			forceSmall: false,
			classSize: true

        };

		var settings = $.extend({}, defaults, args);

		this.each(function(){

			var breakpoints = new Array();

			var windowWidth, currentMedia, element, timeoutOffset;

			// Check the device pixel ratio
			var PixelRatio = 1;
			if(!settings.ignorePixelRatio && window.devicePixelRatio !== undefined) PixelRatio = window.devicePixelRatio;

			// Save off the element so it can be easily used inside a function
			element = $(this);

			//Delete the noscript we don't need it now anyway
			element.find('noscript').remove();

			// Initialise the images
			getCurrentMedia(true);

			// Only call the image resize function 200ms after window stops being resized
			timeoutOffset = false;

			$(window).resize(function(){

				if(timeoutOffset !== false)
					clearTimeout(timeoutOffset);

				timeoutOffset = setTimeout(getCurrentMedia, 200);

			});


			/**
			 * getCurrentMedia
			 *
			 * Checks the window width off the media query types and selects the current one.
			 * Calls the setPicture or setFigure function to set the image.
			 *
			 */
			function getCurrentMedia(init){

				if(init){


					element.find('source').each(function(){

						var media, num;

						media = $(this).attr('media');

						if(media){

							num = media.replace(/[^\d.]/g, '');

							breakpoints.push(parseInt(num));
						}

					});

					breakpoints.sort(function(a,b){return a - b}); //make sure the largest breakpoint is the last

				}

				var c = 0;

				// Check if user defined container, otherwise take window
				if (settings.container == null){

					windowWidth = ($(window).width()) * PixelRatio;

				}else{

					windowWidth = ($(settings.container).width()) * PixelRatio;

				}

				// Set the c variable to the current media width
				$.each(breakpoints, function(i,v){

					if(parseInt(windowWidth) >= parseInt(v) && parseInt(c) <= parseInt(v))
						c = v;

					//console.log("v : " + parseInt(v) + " c : " + parseInt(c));

				});

				if (settings.useLarger ){
					idx = breakpoints.indexOf(c);
					if (idx < breakpoints.length-1) //make sure we're not already using the largest breakpoint
						c = breakpoints[ idx + 1];
				}

				// modification by Louis Eveillard for louiseveillard.com
				if (settings.forceSmall ){
					c = 0;
				}

				if(currentMedia !== c){

					currentMedia = c;
					//console.log("element : " + element.attr('alt'));

					setPicture();
				}

			}


			/**
			 * setPicture
			 *
			 * Pulls the image src and media attributes from the source tags and sets
			 * the src of the enclosed img tag to the appropriate one.
			 *
			 */
			function setPicture(){

				var sizes = new Object();

				element.find('source').each(function(){

					var media, path, num;
					media = $(this).attr('media');
					path = $(this).attr('src');

					if(media)
						num = media.replace(/[^\d.]/g, '');
					else
						num = 0;

					sizes[num] = path;

				});

				if(element.find('img').length == 0){

					var prep = '<img src="' + sizes[currentMedia] + '"'
					if(element.attr('style')) prep += ' style="' + element.attr('style') + '"';
					if(element.attr('alt')) prep += ' alt="' + element.attr('alt') + '"';
					prep += '>';

					if($(settings.insertElement, element).length == 0){

						element.append(prep);

					}else{

						$(settings.insertElement, element).append(prep);

					}

				}else{
					element.find('img').attr('src', sizes[currentMedia]);

				}

				if(settings.inlineDimensions){

					$("<img/>").attr("src", $('img', element).attr("src")).load(function(){
						$('img', element).attr('height', this.height);
						$('img', element).attr('width', this.width);
				    });

				}

			}


			/**
			 * setFigure
			 *
			 * Pulls the image src and and media values from the data attributes
			 * and sets the src of the enclosed img tag to the appropriate one.
			 *
			 */
			function setFigure(){

				var sizes = new Object();

				var mediaObj = element.data();

				$.each(mediaObj, function(media, path){

					var num;

					num = media.replace(/[^\d.]/g, '');

					if(!num)
						num = 0;

					sizes[num] = path;

				});

				if(element.find('img').length == 0){

					var prep = '<img src="' + sizes[currentMedia] + '"';
					if(element.attr('style')) prep += ' style="' + element.attr('style') + '"';
					if(element.attr('title')) prep += ' alt="' + element.attr('title') + '"';
					prep += '>';

					if($(settings.insertElement, element).length == 0){

						element.append(prep);

					}else{

						$(settings.insertElement, element).append(prep);

					}

				}else{

					$('img', element).attr('src', sizes[currentMedia]);

				}

				if(settings.inlineDimensions){

					$("<img/>").attr("src", $('img', element).attr("src")).load(function(){
						$('img', element).attr('height', this.height);
						$('img', element).attr('width', this.width);
				    });

				}

			}

		});

	};

})(jQuery);

/******* origine : jqueryui-easing script, BSD license  ******/

$.extend($.easing,
{
    def: 'easeOutQuad',
    easeInOutQuint: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
        return c/2*((t-=2)*t*t*t*t + 2) + b;
    }

});

/*
 * jQuery Hotkeys Plugin
 * Copyright 2010, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Based upon the plugin by Tzury Bar Yochay:
 * http://github.com/tzuryby/hotkeys
 *
 * Original idea by:
 * Binny V A, http://www.openjs.com/scripts/events/keyboard_shortcuts/
*/

(function(jQuery){

	jQuery.hotkeys = {
		version: "0.8",

		specialKeys: {
			8: "backspace", 9: "tab", 10: "return", 13: "return", 16: "shift", 17: "ctrl", 18: "alt", 19: "pause",
			20: "capslock", 27: "esc", 32: "space", 33: "pageup", 34: "pagedown", 35: "end", 36: "home",
			37: "left", 38: "up", 39: "right", 40: "down", 45: "insert", 46: "del",
			96: "0", 97: "1", 98: "2", 99: "3", 100: "4", 101: "5", 102: "6", 103: "7",
			104: "8", 105: "9", 106: "*", 107: "+", 109: "-", 110: ".", 111 : "/",
			112: "f1", 113: "f2", 114: "f3", 115: "f4", 116: "f5", 117: "f6", 118: "f7", 119: "f8",
			120: "f9", 121: "f10", 122: "f11", 123: "f12", 144: "numlock", 145: "scroll", 186: ";", 191: "/",
			220: "\\", 222: "'", 224: "meta"
		},

		shiftNums: {
			"`": "~", "1": "!", "2": "@", "3": "#", "4": "$", "5": "%", "6": "^", "7": "&",
			"8": "*", "9": "(", "0": ")", "-": "_", "=": "+", ";": ": ", "'": "\"", ",": "<",
			".": ">",  "/": "?",  "\\": "|"
		}
	};

	function keyHandler( handleObj ) {
		if ( typeof handleObj.data === "string" ) {
			handleObj.data = { keys: handleObj.data };
		}

		// Only care when a possible input has been specified
		if ( !handleObj.data || !handleObj.data.keys || typeof handleObj.data.keys !== "string" ) {
			return;
		}

		var origHandler = handleObj.handler,
			keys = handleObj.data.keys.toLowerCase().split(" "),
			textAcceptingInputTypes = ["text", "password", "number", "email", "url", "range", "date", "month", "week", "time", "datetime", "datetime-local", "search", "color", "tel"];

		handleObj.handler = function( event ) {
			// Don't fire in text-accepting inputs that we didn't directly bind to
			if ( this !== event.target && (/textarea|select/i.test( event.target.nodeName ) ||
				jQuery.inArray(event.target.type, textAcceptingInputTypes) > -1 ) ) {
				return;
			}

			var special = jQuery.hotkeys.specialKeys[ event.keyCode ],
				// character codes are available only in keypress
				character = event.type === "keypress" && String.fromCharCode( event.which ).toLowerCase(),
				modif = "", possible = {};

			// check combinations (alt|ctrl|shift+anything)
			if ( event.altKey && special !== "alt" ) {
				modif += "alt+";
			}

			if ( event.ctrlKey && special !== "ctrl" ) {
				modif += "ctrl+";
			}

			// TODO: Need to make sure this works consistently across platforms
			if ( event.metaKey && !event.ctrlKey && special !== "meta" ) {
				modif += "meta+";
			}

			if ( event.shiftKey && special !== "shift" ) {
				modif += "shift+";
			}

			if ( special ) {
				possible[ modif + special ] = true;
			}

			if ( character ) {
				possible[ modif + character ] = true;
				possible[ modif + jQuery.hotkeys.shiftNums[ character ] ] = true;

				// "$" can be triggered as "Shift+4" or "Shift+$" or just "$"
				if ( modif === "shift+" ) {
					possible[ jQuery.hotkeys.shiftNums[ character ] ] = true;
				}
			}

			for ( var i = 0, l = keys.length; i < l; i++ ) {
				if ( possible[ keys[i] ] ) {
					return origHandler.apply( this, arguments );
				}
			}
		};
	}

	jQuery.each([ "keydown", "keyup", "keypress" ], function() {
		jQuery.event.special[ this ] = { add: keyHandler };
	});

})( this.jQuery );

/*!
 * jQuery Scrollstop Plugin v1.1.0
 * https://github.com/ssorallen/jquery-scrollstop
 */
(function($) {
  // $.event.dispatch was undocumented and was deprecated in jQuery 1.7[1]. It
  // was replaced by $.event.handle in jQuery 1.9.
  //
  // Use the first of the available functions to support jQuery <1.8.
  //
  // [1] https://github.com/jquery/jquery-migrate/blob/master/src/event.js#L25
  var dispatch = $.event.dispatch || $.event.handle;

  var special = $.event.special,
      uid1 = 'D' + (+new Date()),
      uid2 = 'D' + (+new Date() + 1);

  special.scrollstart = {
    setup: function(data) {
      var _data = $.extend({
        latency: special.scrollstop.latency
      }, data);

      var timer,
          handler = function(evt) {
            var _self = this,
                _args = arguments;

            if (timer) {
              clearTimeout(timer);
            } else {
              evt.type = 'scrollstart';
              dispatch.apply(_self, _args);
            }

            timer = setTimeout(function() {
              timer = null;
            }, _data.latency);
          };

      $(this).bind('scroll', handler).data(uid1, handler);
    },
    teardown: function() {
      $(this).unbind('scroll', $(this).data(uid1));
    }
  };

  special.scrollstop = {
    latency: 250,
    setup: function(data) {
      var _data = $.extend({
        latency: special.scrollstop.latency
      }, data);

      var timer,
          handler = function(evt) {
            var _self = this,
                _args = arguments;

            if (timer) {
              clearTimeout(timer);
            }

            timer = setTimeout(function() {
              timer = null;
              evt.type = 'scrollstop';
              dispatch.apply(_self, _args);
            }, _data.latency);
          };

      $(this).bind('scroll', handler).data(uid2, handler);
    },
    teardown: function() {
      $(this).unbind('scroll', $(this).data(uid2));
    }
  };

})(jQuery);
