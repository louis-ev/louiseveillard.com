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
		var canvas = $("body").prepend("<canvas id='links' width='1024' height='1551' class=''>");;
		// rattacher le sketch au doc HTML
		canvas.css('visibility',"visible");

		function drawLinks(processing) {
			processing.setup = function() {
				processing.noLoop();
				processing.background(0,0);
				processing.smooth();
			};

			processing.draw = function() {

			};

			processing.drawLink = function( startPointx, startPointy, endPointx, endPointy, p5Color ) {

				processing.noFill();
				processing.strokeWeight(1);

				for( var i=0; i< 1 ; i++ ) {

					var randomInt = processing.random();

					var cred = processing.color(255, 39, 25);
					var cdgreen = processing.color(9, 96, 111);
					var clgreen = processing.color( 125, 193, 200);

					console.log( p5Color );

					var randomToInterval = processing.map( randomInt, 0, 2, 140, 400 );
					randomToInterval = 240;

					var noHorizontalLine = 0;
					if( Math.abs(startPointy - endPointy) < 20 ) {
						noHorizontalLine = 20;
					}

					processing.stroke( p5Color );
//						processing.stroke( processing.lerpColor( clgreen, cdgreen, randomInt ) );
//						processing.stroke( clgreen );

					processing.pushMatrix();

					processing.scale(2);

					processing.bezier( startPointx, startPointy, (startPointx + randomToInterval ), startPointy, (endPointx - randomToInterval ), endPointy + noHorizontalLine, endPointx, endPointy );

					processing.popMatrix();

				}
			}

			processing.drawBg = function() {

				if( $("body").hasClass("jour") ) {
					processing.fill( 242,242,242);
				} else {
					processing.fill( 34,34,34);
				}

				processing.noStroke();
				processing.rect( 0, 0, processing.width, processing.height);


			}
			processing.eraseBg = function() {

				if( $("body").hasClass("jour") ) {
					processing.fill( 242,242,242);
				} else {
					processing.fill( 34,34,34);
				}

				processing.noStroke();
				processing.rect( 0, 0, processing.width, processing.height);


			}

			processing.updateSize = function( width, height ) {
			  processing.size(width * 2, height * 2);
			}

		}

		var canvasJS = document.getElementById("links");
		var processingInstance = new Processing(canvasJS, drawLinks);


		$(".module--intro a").each(function() {
  		thisColor = $this.css("border-bottom-color");
  		thisLink = $this.attr("data-link");
  		canvasCV.drawAllLinks( $this, thisLink, thisColor );
		});
  },

	drawAllLinks : function( $hoveredLink, hoveredDataLink, thisColor ) {
		var thisSketch = Processing.getInstanceById('links');
		//thisColor = thisColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

		//var p5Color = thisSketch.color( parseInt(thisColor[1],10), parseInt(thisColor[2],10), parseInt(thisColor[3],10) );
		var p5Color = "#ff00ff";

		console.log( "thisColor " + thisColor );
		//console.log( "parseInt(thisColor[1],10) " + parseInt(thisColor[1],10) );


		//thisSketch.background(0,90);


		var $linkTo = $("#navbar").find("a[data-link='" + hoveredDataLink + "']").not( $hoveredLink );

		if ( $linkTo.length > 0 ) {

			$linkTo.each( function() {

				$thisLinkTo = $(this);

				console.log("LINK $this :" + $hoveredLink.text() + " $linkTo : " + $thisLinkTo.text() + " thisColor : " + thisColor );

/*
				if ( $hoveredLink.closest(".content").hasClass("realisations") === true ) {
					thisPosLeft = $hoveredLink.offset().left - 10;
					thisPosTop = $hoveredLink.offset().top + $hoveredLink.height()/2 + 2;
				} else {
					thisPosLeft = $hoveredLink.offset().left;
					thisPosTop = $hoveredLink.offset().top + $hoveredLink.height() + 1;
				}
				if ( $thisLinkTo.closest(".content").hasClass("realisations") === true ) {
					linkPosLeft = $thisLinkTo.offset().left - 10;
					linkPosTop = $thisLinkTo.offset().top + $thisLinkTo.height()/2 + 2;
				} else {
					linkPosLeft = $thisLinkTo.offset().left;
					linkPosTop = $thisLinkTo.offset().top + $thisLinkTo.height() + 1;
				}
*/
				var locationthisPos = getLocationOfDatalink( $hoveredLink, false );
				var locationlinkPos = getLocationOfDatalink( $thisLinkTo, true );

				thisSketch.drawLink( locationthisPos.left - 1, locationthisPos.bottom, locationlinkPos.left + 1, locationlinkPos.bottom, p5Color );

			});
		}

	},

	resize : function () {
	},

	loopMeUp: function() {

	},

	drawAllLinksAtOnce : function() {

		$("#navbar a[data-link]").each(function() {

			$this = $(this);
			thisLink = $this.attr("data-link");

			var thisSketch = Processing.getInstanceById('links');

			if ( $("body").hasClass("navbarouverte") && thisLink !== undefined ) {

				console.log( " thisLink : " + thisLink );

				if ( thisLink !== undefined ) {
					thisColor = $this.css("border-bottom-color");
					canvasCV.drawAllLinks( $this, thisLink, thisColor );
				} else {

				}

			}

		});
	}
};
jQuery.fn.reverse = [].reverse;

var theProjetList = {
	init : function() {
		// binder un event mouse : au survol sur un a, passer le gradient en is--away et charger l'image qui correspond
		var $allProjets = $(".module--projet");
		$('.module--projetList .module--projetList--projetName--links').each( function() {
  		$(this).on('mouseover', function() {
  			$("body").attr("module--gradient_overlay", "is--away");
  			projetIndex = $(this).parent("li").attr("data-index");
  			$thisProjet = $(".module--projet[data-index=" + projetIndex + "]");

  			$thisProjet.addClass("is--shown");

  		});
  		$(this).on('mouseleave', function() {

  			projetIndex = $(this).parent("li").attr("data-index");
  			$thisProjet = $(".module--projet[data-index=" + projetIndex + "]");
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

    // insérer les h3 juste avant les premiers de chaque
/*
    $projetList.find("h3[data-type]").each(function() {
      dtype = $(this).attr("data-type");
      numPremierProjet = $items.not("h3").filter( function() {
        return $(this).attr("data-type") == dtype;
      }).first().attr("data-num");
      $(this).attr("data-num", numPremierProjet);
    });
*/

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
/*
    var $filteredPosts = $(".module--projetList--projetName--links[data-type=" + filterByType + "]");
    var filteredList = '';
    if( filterByType === 'vr')
      filteredList = $('<h3>Projets en réalité virtuelle sur lesquels j´ai travaillé</h3>');
    else if( filterByType === 'sites')
      filteredList = $('<h3>Sites réalisés récemment</h3>');
    else if( filterByType === 'installations')
      filteredList = $('<h3>Installations créées</h3>');
    else if( filterByType === 'outils-pedagogiques')
      filteredList = $('<h3>Outils pédagogiques développés</h3>');
    $filteredPosts.parent("li").reverse().prependTo( $projetList);
    filteredList.prependTo( $projetList);
*/

  },

};


$(document).ready(function() {
	theProjetList.init();
	theIntroLinks.init( $(".module--projetList"));
	//canvasCV.init();

});