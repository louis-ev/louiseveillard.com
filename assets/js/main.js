/*
	 map values :
		var num = 5;
		console.log( num.map( 0 , 10 , -50 , 50 ) ); // 0
		console.log( num.map( -20 , 0 , -100 , 100 ) ); // 100
*/

Number.prototype.map = function ( in_min , in_max , out_min , out_max ) {
  var theNumber = ( this - in_min ) * ( out_max - out_min ) / ( in_max - in_min ) + out_min;
  if ( out_max > out_min ) {
	  if ( theNumber > out_max ) theNumber = out_max;
	  if ( theNumber < out_min ) theNumber = out_min;
  } else {
	  if ( theNumber < out_max ) theNumber = out_max;
	  if ( theNumber > out_min ) theNumber = out_min;
  }
  return theNumber;
}


var canvasCV = {
	init : function() {

    init_pjs = 768;
		if ( $(window).width() > init_pjs ) {
	    var scriptSrc = './bower_components/Processing.js/processing.min.js?' + new Date().getTime();
	    //console.log( "script : " , script);
			var script = document.createElement('script');
			script.src = scriptSrc;

			script.onload = function() {
		    canvasCV.start();
			};

			var head = document.getElementsByTagName('head')[0];
			head.appendChild(script);
  	}
  },

  start : function() {

		// trouver le canvas dans la page
		var canvas = $("body").prepend("<canvas id='links' width='100%' height='100%'></canvas>");;
		// rattacher le sketch au doc HTML
		canvas.css('visibility',"visible");

		function drawLinks(processing) {
			processing.setup = function() {
				processing.noLoop();
				//processing.background( 21);
				processing.smooth();
			};

			processing.draw = function() {

			};

			processing.drawLink = function( startPointx, startPointy, endPointx, endPointy, p5Color ) {

				processing.noFill();
				processing.strokeWeight(1);

				for( var i=0; i<1 ; i++ ) {

					var randomInt = processing.random();

					var cred = processing.color(255, 39, 25);
					var cdgreen = processing.color(9, 96, 111);
					var clgreen = processing.color( 125, 193, 200);

//					console.log( p5Color );

					var randomToInterval = processing.map( randomInt, 0, 2, 140, 400 );
					//randomToInterval = 240;

					var noHorizontalLine = 0;
					if( Math.abs(startPointy - endPointy) < 20 ) {
						noHorizontalLine = 20;
					}

//					processing.stroke( p5Color );
					processing.stroke( cdgreen );

					processing.pushMatrix();

					processing.scale(2);
					processing.bezier( startPointx, startPointy, (startPointx + randomToInterval ), startPointy, (endPointx - randomToInterval ), endPointy + noHorizontalLine, endPointx, endPointy );

					processing.popMatrix();

				}
			}

			processing.eraseBg = function() {
				processing.fill( 24);
				processing.noStroke();
				processing.rect( 0, 0, processing.width, processing.height);
			}

			processing.updateSize = function( width, height ) {
			  processing.size(width * 2, height * 2);
			}

		}

		var canvasJS = document.getElementById("links");
		var processingInstance = new Processing(canvasJS, drawLinks);

		var thisSketch = Processing.getInstanceById('links');

		thisSketch.updateSize( $("body").width(), $(".module--cv").height());
		thisSketch.eraseBg();

		var alreadyLinked = new Array([]);

		// donner un ID à chaque lien qu'il va falloir traiter
		$(".module--cv a[data-link]").each(function(i) {
  		$(this).data( 'linkID', i);
		});

		$(".module--cv a[data-link]").each(function() {
  		var $this = $(this);
  		var thisLink = $this.attr("data-link");
  		var thisColor = "#ff00ff";

  		// return la liste de ce qui a été relié. La mettre à la suite de alreadyLinked
  		canvasCV.drawAllLinks( $this.data( 'linkID'), thisSketch, $this, thisLink, thisColor, alreadyLinked);
		});
  },

	drawAllLinks : function( linkID, thisSketch, $this, thisLink, thisColor, alreadyLinked) {

		//thisColor = thisColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

		//var p5Color = thisSketch.color( parseInt(thisColor[1],10), parseInt(thisColor[2],10), parseInt(thisColor[3],10) );

		//console.log( "thisColor " + thisColor );
		//console.log( "parseInt(thisColor[1],10) " + parseInt(thisColor[1],10) );
		//thisSketch.background(0,90);

		var $linkTo = $(".module--cv").find("a[data-link='" + thisLink + "']").not( $this );

		if ( $linkTo.length > 0 ) {

			$linkTo.each( function() {
				$thisLinkTo = $(this);
				//console.log("LINK $this :" + $this.text() + " $linkTo : " + $thisLinkTo.text() + " thisColor : " + thisColor );

        // faire un tableau de ces deux liens
        var thoseTwoLinks = [linkID, $thisLinkTo.data( 'linkID')].sort();
        var thoseLinksHaveBeenLinked = false;

        var boucles = 0;
        // checker si on a déjà relier ces deux points ensemble
        for( var i = 0, len = alreadyLinked.length; i < len; i++ ) {
          var a = alreadyLinked[i];
          var b = thoseTwoLinks;

          boucles++;
          if( alreadyLinked[i][0] === thoseTwoLinks[0]){
            if( alreadyLinked[i][1] === thoseTwoLinks[1]) {
              thoseLinksHaveBeenLinked = true;
              // supprimer cette paire du tableau (optimisation !)
               alreadyLinked.splice(i, 1);
              break;
            }
          }
        }

        console.log( 'boucles ' + boucles);

        if( !thoseLinksHaveBeenLinked) {

  				var locationthisPos = canvasCV.getLocationOfDatalink( $this, false );
  				var locationlinkPos = canvasCV.getLocationOfDatalink( $thisLinkTo, true );

  				thisSketch.drawLink( locationthisPos.left - 1, locationthisPos.bottom, locationlinkPos.left + 1, locationlinkPos.bottom, thisColor );

  				$thisLinkTo.addClass('is--linkedto');
  				$this.addClass('is--linkedfrom');

          // ajouter dans le tableau de ceux qu'on a déjà relié le point concerné
          alreadyLinked.push(thoseTwoLinks);

        }
			});
		}

    return alreadyLinked;
	},

	loopMeUp: function() {

	},

	drawAllLinksAtOnce : function() {

	},


	getLocationOfDatalink : function( $thisLink, firstChar ) {
		var $elem = $thisLink;
		var text = $elem.html();

		if( !firstChar ) {
			var newText = text + '<span class="position-of-eles"></span>';
		} else {
			var newText = '<span class="position-of-eles"></span>' + text;
		}
		$elem.html(newText); //Set wrapper
		var offset = $elem.find(".position-of-eles").offset();
		var height = $elem.find(".position-of-eles").height();
		offset.bottom = offset.top + height + 1.5;

//		console.log( offset );

		$elem.html(text)    ; //Place back

		return offset;
	},

};
jQuery.fn.reverse = [].reverse;

var theProjetList = {
	init : function() {
		// binder un event mouse : au survol sur un a, passer le gradient en is--away et charger l'image qui correspond

		var $allProjets = $(".module--projet_short");
		$('.module--projetList .module--projetList--projetName--links').each( function() {

  		$(this).on('mouseover', function() {

  			$("body").attr("module--gradient_overlay", "is--away");
  			projetIndex = $(this).parent("li").attr("data-index");
  			$thisProjet = $allProjets.find(".module--projet--header[data-index=" + projetIndex + "]").closest(".module--projet");

  			$thisProjet.addClass("is--shown");

  		});
  		$(this).on('mouseleave', function() {

  			projetIndex = $(this).parent("li").attr("data-index");
  			$thisProjet = $allProjets.find(".module--projet--header[data-index=" + projetIndex + "]").closest(".module--projet");
  			$thisProjet.removeClass("is--shown");

  			$("body").attr("module--gradient_overlay", "");

  		});
		});

	},
};

var theIntroLinks = {

	init : function( $projetList) {

    click = 0;
    var $items = $projetList.find( ".isotope--item");
    $items.each(function() {
      if( $(this).attr("data-index") !== undefined)
        $(this).attr("data-num", $(this).attr("data-index"));
    });


  	$projetList.isotope({
      itemSelector: '.isotope--item',
      layoutMode: 'vertical',
/*
		  filter: function() {
        // _this_ is the item element. Get text of element's .number
        var number = $(this).attr('data-type');
        // return true to show, false to hide
        return number === 'vr';
      },
      hiddenStyle: {
        opacity: .4
      },
*/
      getSortData: {
        'number': function( item ) {
          $item = $(item);
          var num = $item.attr('data-num');
          if( num !== undefined)
            return parseInt( num, 10);
          else
            return false;
        },
        'type': function( item ) {
          $item = $(item);
          var type = $item.attr('data-type');
          if( type !== undefined)
            return type;
          else
            return false;
        },
		  },
		  sortBy : ['number', 'type'],
    });


		$('.module--intro a').on('click', function() {
  		$('.module--intro a').not( $(this)).removeClass("is--active");
      $(this).toggleClass("is--active");
      theIntroLinks.filterProjects( $projetList, $items);
    });
	},

	filterProjects : function( $projetList, $items) {
    // remettre tous les projets dans une section "derniers projets réalisés"
    var filterByType = $('.module--intro a.is--active').attr("href");
    if( filterByType === undefined) {
      $projetList.find("h3[data-type]").hide();
      $projetList.find("h3:not([data-type])").show();

      $items.each(function() {
        if( $(this).attr("data-index") !== undefined)
          $(this).attr("data-num", $(this).attr("data-index"));
      });
      $projetList.isotope( 'updateSortData', $items);
      $projetList.isotope({
		    sortBy : ['number', 'type'],
      });
    } else {

      $projetList.find("h3[data-type]").show();
      $projetList.find("h3:not([data-type])").hide();

      filterByType = filterByType.substring(1);
      click++;
      var numberOfProjets = $items.length;

      //$projetList.find("h3[data-type='" + filterByType + "']").show();
      var $selectedItems = $items.filter( function() {
        return $(this).attr("data-type") == filterByType;
      });

      var index = 0;

      $selectedItems.each(function() {
        currentCount = $(this).attr("data-num");
        $(this).attr("data-num", index);
      });
      index++;
      $items.not( $selectedItems).each(function() {
        currentCount = $(this).attr("data-num");
        $(this).attr("data-num", index);
      });

      $projetList.isotope( 'updateSortData', $items);
      $projetList.isotope({
		    sortBy : ['number', 'type'],
      });
    }

  },

};


var theProjetView = {


	init : function() {


    // fonction qui gère le zoom-in sur l'image du haut
    var zoomedIn = false;
    $('.module--projet--visuel').on('click', function(e) {
      zoomedIn = !zoomedIn;
      $('body').attr( 'data-visuel', zoomedIn ? 'zoomedIn' : '');
      return false;
    });

    var wHeight = window.innerHeight;
    var $visuelTop = $(".module--projet_full .module--projet--visuel").first();
    if( window.innerWidth > 700 && $visuelTop.length > 0) {
      theProjetView.changeVisuelOpacity( wHeight, $visuelTop);
    }
  },

  changeVisuelOpacity : function( wHeight, $visuelTop) {

    scrollY = window.pageYOffset;
    var cssOpacity = scrollY.map( 0, wHeight, 1, 0)
    $visuelTop.css("opacity", cssOpacity);

		$(window).on('scroll', function () {
      scrollY = window.pageYOffset;
      if( scrollY < wHeight) {
        var cssOpacity = scrollY.map( 0, wHeight * .34, 1, 0)
        $visuelTop.css("opacity", cssOpacity);
      }
    });
  },

};

$(document).ready(function() {
	theProjetList.init();
	theIntroLinks.init( $(".module--projetList"));

	theProjetView.init();

	$('body').removeClass("is--loading");

	if( $(".module--cv").length > 0)
	setTimeout( canvasCV.init(), 400);
});