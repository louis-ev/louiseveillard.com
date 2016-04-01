/*
	 map values :
		var num = 5;
		console.log( num.map( 0 , 10 , -50 , 50 ) ); // 0
		console.log( num.map( -20 , 0 , -100 , 100 ) ); // 100
*/

Number.prototype.map = function ( in_min , in_max , out_min , out_max ) {
  var theNumber = ( this - in_min ) * ( out_max - out_min ) / ( in_max - in_min ) + out_min;
  if ( out_max > out_min ) {
	  if ( theNumber > out_max ) { theNumber = out_max; }
	  if ( theNumber < out_min ) { theNumber = out_min; }
  } else {
	  if ( theNumber < out_max ) { theNumber = out_max; }
	  if ( theNumber > out_min ) { theNumber = out_min; }
  }
  return theNumber;
};

var canvasCV = {
	init : function() {

    init_pjs = 768;
		if ( $(window).width() > init_pjs ) {
	    var scriptSrc = 'http://louiseveillard.com/bower_components/Processing.js/processing.min.js?' + new Date().getTime();
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
		var canvas = $("main").prepend("<canvas id='links' width='100%' height='100%'></canvas>");
		// rattacher le sketch au doc HTML

		function drawLinks(processing) {
			processing.setup = function() {
				processing.noLoop();
				//processing.background( 21);
				processing.smooth();
			};

			processing.draw = function() {

			};

			processing.drawLink = function( startPointx, startPointy, endPointx, endPointy, p5Color, strokeWeight ) {

				processing.noFill();
				processing.strokeWeight(strokeWeight);

				for( var i=0; i<1 ; i++ ) {

					var randomInt = processing.random(0, 2);

					var cred = processing.color(255, 39, 25);
					var cdgreen = processing.color(9, 96, 111);
					var clgreen = processing.color( 125, 193, 200);

					//var randomToInterval = processing.map( randomInt, 0, 2, 240, 800 );
					randomToInterval = 400;

					var noHorizontalLine = 0;
					if( Math.abs(startPointy - endPointy) < 20 ) {
						noHorizontalLine = 55;
					}

//					processing.stroke( p5Color );

					processing.pushMatrix();

					processing.scale(2);

					var a1dx = startPointx;
					var a1dy = startPointy;
					var a1fx = startPointx + randomToInterval;
					var a1fy = startPointy;

          var a2dx = endPointx - randomToInterval;
          var a2dy = 	endPointy + noHorizontalLine;
          var a2fx = endPointx;
          var a2fy = endPointy;

					processing.stroke( 0,0,0);
/*
          processing.line( a1dx, a1dy, a1fx, a1fy);
          processing.line( a2dx, a2dy, a2fx, a2fy)
*/
					processing.stroke( p5Color[0], p5Color[1], p5Color[2]);
					processing.bezier( a1dx, a1dy, a1fx, a1fy, a2dx, a2dy, a2fx, a2fy);

					processing.popMatrix();

				}
			};

			processing.eraseBg = function( backgroundColor, bgOpacity) {
  			if( bgOpacity === undefined) {
  			  bgOpacity = 255;
  		  }
				processing.fill( backgroundColor[0], backgroundColor[1], backgroundColor[2], bgOpacity);
				processing.noStroke();
				processing.rect( 0, 0, processing.width, processing.height);
			};

			processing.updateSize = function( width, height ) {
			  processing.size(width * 2, height * 2);
			};

		}

		var canvasJS = document.getElementById("links");
		var processingInstance = new Processing(canvasJS, drawLinks);

		var thisSketch = Processing.getInstanceById('links');
		var cbg = $("body").css("background-color").replace(/^(rgb|rgba)\(/,'').replace(/\)$/,'').replace(/\s/g,'').split(',');

		thisSketch.updateSize( $("body").width(), $("body").height());
		thisSketch.eraseBg( cbg);

		var alreadyLinked = new Array([]);

		// donner un ID à chaque lien qu'il va falloir traiter
		$(".module--cv a[data-link]").each(function(i) {
  		$(this).data( 'linkID', i);
		});


    var c = $(".module--cv a[data-link]").first().css("border-bottom-color");
    var linksUnderlineColor = c.replace(/^(rgb|rgba)\(/,'').replace(/\)$/,'').replace(/\s/g,'').split(',');

		$(".module--cv a[data-link]").each(function() {
  		var $this = $(this);
  		var thisLink = $this.attr("data-link");

  		// return la liste de ce qui a été relié. La mettre à la suite de alreadyLinked
  		canvasCV.drawAllLinks( $this.data( 'linkID'), thisSketch, $this, thisLink, linksUnderlineColor, alreadyLinked);

  		//au survol
			$this.on("mouseover", function() {
  			var c = $this.css("border-bottom-color");
        var cbg = $("body").css("background-color").replace(/^(rgb|rgba)\(/,'').replace(/\)$/,'').replace(/\s/g,'').split(',');
        var linksUnderlineColor = c.replace(/^(rgb|rgba)\(/,'').replace(/\)$/,'').replace(/\s/g,'').split(',');

        $(".module--cv a[data-link]").removeClass('is--linkedto').removeClass('is--linkedfrom');
        $this.addClass('is--currentlyLinkedTo');
        thisSketch.eraseBg( cbg, 225);
				canvasCV.drawAllLinks( $this.data( 'linkID'), thisSketch, $this, thisLink, linksUnderlineColor);
		  });
		});
		$(canvasJS).css("opacity", 1);
  },

	drawAllLinks : function( linkID, thisSketch, $this, thisLink, thisColor, alreadyLinked) {

		var $linkTo = $(".module--cv").find("a[data-link='" + thisLink + "']").not( $this );

		if ( $linkTo.length > 0 ) {

			$linkTo.each( function() {

				$thisLinkTo = $(this);
				//console.log("LINK $this :" + $this.text() + " $linkTo : " + $thisLinkTo.text() + " thisColor : " + thisColor );

        // faire un tableau de ces deux liens
        var thoseTwoLinks = [linkID, $thisLinkTo.data( 'linkID')].sort();
        var thoseLinksHaveBeenLinked = false;

        var boucles = 0;

        var thisStroke;
        // checker si on a bien passé un alreadyLinked
        if( alreadyLinked !== undefined) {
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
          thisStroke = 1;
        }
        // si on en a pas passé c'est qu'on est dans le cas du soulignement d'un élément hover
        else {
          $thisLinkTo.addClass('is--currentlyLinkedTo');
          $linkTo.removeClass('is--linkedfrom');
          thisStroke = 1;
        }

        if( !thoseLinksHaveBeenLinked) {

  				var locationthisPos = canvasCV.getLocationOfDatalink( $this, false );
  				var locationlinkPos = canvasCV.getLocationOfDatalink( $thisLinkTo, true );

  				thisSketch.drawLink( locationthisPos.left - 1, locationthisPos.bottom, locationlinkPos.left + 1, locationlinkPos.bottom, thisColor, thisStroke);

  				$thisLinkTo.addClass('is--linkedto');
  				$this.addClass('is--linkedfrom');

          // ajouter dans le tableau de ceux qu'on a déjà relié le point concerné
          if( alreadyLinked !== undefined) {
            alreadyLinked.push(thoseTwoLinks);
          }
        }
			});
		}

    return alreadyLinked;
	},


	getLocationOfDatalink : function( $thisLink, firstChar ) {
		var $elem = $thisLink;
		var text = $elem.html();
    var newText;
		if( !firstChar ) {
			newText = text + '<span class="position-of-eles"></span>';
		} else {
			newText = '<span class="position-of-eles"></span>' + text;
		}
		$elem.html(newText); //Set wrapper

		var offset = $elem.find(".position-of-eles").offset();
		var height = $elem.find(".position-of-eles").height();
		offset.bottom = offset.top + height + 1.4;

//		console.log( offset );

		$elem.html(text)    ; //Place back

		return offset;
	},

};
jQuery.fn.reverse = [].reverse;

var theProjetList = {
	init : function( $projetListEtVisuels) {
		// binder un event mouse : au survol sur un a, passer le gradient en is--away et charger l'image qui correspond

		var $allProjetsVisuel = $projetListEtVisuels.find(".module--projetList--visuelWrapper--visuel");

    var slice = [].slice;
    document.addEventListener('lazybeforeunveil', function(e){
        var children = e.target.getAttribute('data-lazyload-children');
        if(children){
            $unveilchildren = slice.call(e.target.querySelectorAll(children));
            $unveilchildren.forEach(lazySizes.loader.unveil);
        }
    });

		$projetListEtVisuels.find(".module--projetList--titles--projetName--links").each( function() {

  		$(this).on('mouseover', function() {
  			projetIndex = $(this).parent(".module--projetList--titles--projetName").attr("data-index");
  			$thisProjetVisuel = $allProjetsVisuel.filter(function(){
                                                   return $(this).attr('data-index') === projetIndex;
                                                });
        $thisProjetVisuel.addClass("is--shown");
  			$("body").attr("module--projetVisuel", "is--shown");
  		});
  		$(this).on('mouseleave', function() {
  			projetIndex = $(this).parent("li").attr("data-index");
  			$thisProjetVisuel = $allProjetsVisuel.filter(function(){
                                                   return $(this).attr('data-index') === projetIndex;
                                                });
  			$thisProjetVisuel.removeClass("is--shown").addClass("was--shown");
  			$("body").attr("module--projetVisuel", "");
  		});
		});

	},
};

var theIntroLinks = {

	init : function( introLinks, $projetList) {

    click = 0;
    var $items = $projetList.find( ".isotope--item");
    $items.each(function() {
      if( $(this).attr("data-selected") === undefined){
        $(this).attr("data-selected", 0);
      }
    });

  	$projetList.isotope({
      itemSelector: '.isotope--item',
      layoutMode: 'fitRows',
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
        'selected': function( item ) {
          $item = $(item);
          var selected = $item.attr('data-selected');
          if( selected !== undefined){
            return parseInt( selected, 10);
          } else {
            return false;
          }
        },
        'index': function( item ) {
          $item = $(item);
          var index = $item.attr('data-index');
          if( index !== undefined) {
            return parseInt( index, 10);
          } else {
            return false;
          }
        },
        'number': function( item ) {
          $item = $(item);
          var num = $item.attr('data-num');
          if( num !== undefined) {
            return parseInt( num, 10);
          } else {
            return false;
          }
        },
        'type': function( item ) {
          $item = $(item);
          var type = $item.attr('data-type');
          if( type !== undefined) {
            return type;
          } else {
            return false;
          }
        },
		  },
		  sortBy : ['index', 'number', 'type', ],
    });
		$(document).on('click', introLinks, function() {
  		$('.module--intro a').not( $(this)).removeClass("is--active");
      $(this).toggleClass("is--active");
      theIntroLinks.filterProjects( $projetList, $items);
    });
	},

	filterProjects : function( $projetList, $items) {
    // remettre tous les projets dans une section "derniers projets réalisés"
    var filterByType = $('.module--intro a.is--active').attr("href");
    if( filterByType === undefined) {
      $projetList.find("h3[data-type]").addClass('is--hidden');
      $projetList.find("h3:not([data-type])").removeClass('is--hidden');

      $projetList.isotope( 'updateSortData', $items);
      $projetList.isotope({
		    sortBy : ['index', 'number', 'type'],
      });

      $projetList.removeClass("is--sorted");

    } else {

      filterByType = filterByType.substring(1);
      click++;
      var numberOfProjets = $items.length;

      //$projetList.find("h3[data-type='" + filterByType + "']").show();
      var $selectedItems = $items.filter( function() {
        return $(this).attr("data-type") === filterByType;
      });

      $selectedItems.each(function() {
        $(this).attr("data-selected", 0);
      });


      $items.not( $selectedItems).each(function() {
        $(this).attr("data-selected", 1);
      });

      $projetList.find("h3[data-type]").removeClass('is--hidden');
      $projetList.find("h3:not([data-type])").addClass('is--hidden');

      $projetList.isotope( 'updateSortData', $items);
      $projetList.isotope({
		    sortBy : ['selected', 'number', 'type', 'index'],
      });
      $projetList.addClass("is--sorted");
    }

  },

};

// pas encore utilisé
var createCustomFavicon = {
  init : function() {

  	var canvas = document.createElement('canvas'),
  		  ctx,
        img = document.createElement('img'),
        link = document.getElementById('favicon');

  	if (canvas.getContext) {
  	  canvas.height = canvas.width = 32; // set the size
  	  ctx = canvas.getContext('2d');

  		ctx.beginPath();
  		ctx.arc(16, 16, 16, 0, Math.PI*2, true);

  		ctx.closePath();
    	ctx.fillStyle = couleurSecondaire;
    	ctx.fill();

  		ctx.globalAlpha=0.8; // Half opacity
  		ctx.beginPath();
  		ctx.arc(16, 16, 8, 0, Math.PI*2, true);
  		ctx.closePath();

    	ctx.fillStyle = couleurPrimaire;
    	ctx.fill();

    	link.href = canvas.toDataURL('image/png');
  	}
  },
};

var theProjetView = {

	init : function() {

    window.scrollY = window.pageYOffset;

    // fonction qui gère le zoom-in sur l'image du haut
    var zoomedIn = false;
    $(document).on('click', '.module--projet--visuel', function(e) {
      zoomedIn = !zoomedIn;
      theProjetView.zoomIn( zoomedIn);
      return false;
    });

    // animation sur le header avec opacity
    var wHeight = window.innerHeight;
    var $visuelTop = $(".module--projet_full .module--projet--visuel");
    if( window.innerWidth > 700 && $visuelTop.length > 0) {
      theProjetView.changeVisuelOpacity( wHeight, $visuelTop);
    }

    // check pour les vidéos
    $(document).on('lazybeforeunveil', function(e){
      var isVideo = $(e.target).is("video");
      if( isVideo){
        $(e.target).attr("autoplay", true);
        e.target.play();
      }
    });

  },

  zoomIn : function( goZoom) {
    $parentProjet = $( '.module--projet');
    $("body")
      .attr( 'data-visuel', goZoom ? 'zoomedIn' : '')
      .css( "overflow", goZoom ? 'hidden' : '')
    ;
    if( scrollY > 0) {
      $('html, body').animate({scrollTop : 0},400, function() {
      });
    }
  },

  changeVisuelOpacity : function( wHeight, $visuelTop) {

    var cssOpacity = scrollY.map( 0, wHeight, 1, 0);
		$(window).on('scroll', function () {
      window.scrollY = window.pageYOffset;
      if( scrollY < wHeight) {
        var cssOpacity = scrollY.map( 0, wHeight * 0.44, 1, 0);
        $visuelTop.css("opacity", cssOpacity);
      }
    });
  },

};


function pageInit() {

  // pour la vue projet
	theProjetView.init();

  // pour l'affichage du CV
	if( $(".module--cv").length > 0) {
	  setTimeout( canvasCV.init(), 400);
  }

  // fin de chargement
  $('body').removeClass("is--loading");

}


var pjaxNav = {

  init : function() {

    $(document).on( "click", "a", function( event ) {
        var elem = $(this);
        if( elem.is(".logo") || elem.is("[data-goto=infos]")) {
          linkLocation = $(this).attr("href");
          pjaxNav.loadInPJAX( linkLocation, true);
          return false;
        } else
        // click "PROJETS"
        if( elem.is("[data-goto=projets]")) {
          if( $(".module--projetList").length > 0) {
            $("body").css("pointer-events", "none");
            $('html, body').animate({
              scrollTop: $(".module--projetList").offset().top
            }, 800, function(){
            $("body").css("pointer-events", "");
            });
            return false;
          }
        }
        if( elem.is(".module--projetList--titles--projetName--links")) {
          linkLocation = $(this).attr("href");
          // récupérer le visuel clické, le passer en "is--visuelProjet"
    			$(".module--projetList .module--projetList--visuelWrapper--visuel")
    			  .filter(function() {
               return $(this).attr('data-index') === projetIndex;
            })
            .addClass("is--visuelProjet")
          ;

          pjaxNav.loadInPJAX( linkLocation, true);
          return false;
        }
    });

  	$.pjax.defaults.maxCacheLength = 3;
  	$.pjax.defaults.timeout = 2250;

  	$(document).on('pjax:beforeReplace', function ( event, contents, options) {
  	});

    $(document).on("pjax:end", function(event, data) {

      linkToContent = event.target.baseURI;

      // si c'est un fail (cas typique d'un projet qui génère ses thumbs)
      if( data.statusText === "timeout") {
        return false;
      }

  		$(".module--projetList--titles--projetName--links").filter( function() {
      	return $(this).attr("href") === linkToContent;
    	}).parents(".module--projetList--titles--projetName").addClass("is--visited");

      $("body")
        .attr("data-template", pjaxInfos.template)
        .attr("data-intended-template", pjaxInfos.intendedtemplate)
        .attr("data-rubrique", pjaxInfos.rubrique)
        .attr("data-index", pjaxInfos.projetIndex);

      // si on a affaire à une page projet, on démarre en zoomedin
      if( pjaxInfos.template === "projet") {
        theProjetView.zoomIn(true);
        // à la fin de l'animation de cet élément, repasser en zoomIn == false
        $("body").removeClass("is--ajax_loading");
        setTimeout( function() {
          $(".module--projetList .module--projetList--visuelWrapper--visuel").removeClass("is--visuelProjet");
          setTimeout( function() {
           theProjetView.zoomIn(false);
          }, 400);
        }, 600);
        pageInit();
        return;
      }
      $("body").removeClass("is--ajax_loading").css( "overflow", '');
      pageInit();
    });


  },

  loadInPJAX : function( thisURL, withAnim) {

		$("body").addClass("is--ajax_loading");
  	console.log("PJAX to : " + thisURL);
  	if( typeof _gaq !== 'undefined') { _gaq.push(['_trackPageview', thisURL]); }
  	setTimeout( function() {
  		//$('html, body').animate({scrollTop:0}, 100);
  	  $.pjax({
  	    "url": thisURL,
  	    "fragment": "#pjax-container",
  	    "container": "#pjax-container",
  	  });
    }, 600);

  },

};

$(document).ready(function() {

	// pour le bloc intro, associé à la liste des projets
	theIntroLinks.init( '.module--intro a', $(".module--projetList--titles"));

  // pour la liste des projets
	theProjetList.init( $(".module--projetList"));
  pjaxNav.init();
  FastClick.attach(document.body);

  pageInit();
});