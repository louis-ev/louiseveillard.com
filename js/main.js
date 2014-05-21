// TODO
// — ajouter le CV
// — stop le scroll entre les proj (?)
// — scroll au clavier, j/k = projet suivant précédent, haut/bas gauche/droite = img suivante/prec, // en cours
// — repères sur la scrollbar, pour visualiser chaque projet

(function() {

  var $;

  $ = jQuery;

  $(function() {

	window.articleMarginTop = 0;
	window.bodyHeight;

	var articleVu = '';
	var $viewport = $('html, body');
	var scrollOnReady = '';
	var imgActuelle = 0;
	var newArticleVu = $('article.article').first();
	var gotoByScroll, adjustHeight, articleProche, activeArticle, detectArticle, restartDetectArticle;

	(function () {

		//$('.lienprojets>a').attr('href', '');
		// et virer les name des article, pour mieux controler le scroll de projet à projet
		$('.article').each( function() {
			var $this = $(this);
			var thisId = $this.attr('id');
			$this.attr('data-id', thisId).attr('id', '');
		});

		// si hashtag dans l'url, changer le active du header
		if (window.location.hash !== '' && window.location.hash !== '#') {
			var projetHash = window.location.hash.substr(1);
	 		//console.log("hash : " + projetHash);
			scrollOnReady = projetHash;

		}
		// si pas de hashtag, mettre le premier projet en class active
		else {
			$('.lienprojets > a').removeClass('active').first().addClass('active');

			//console.log("pas de hash");
		}



	})();


	$(document).ready(function() {

		// calculer la hauteur du header dès que le doc est chargé
		adjustHeight();
 		mapScroll.init();
		backToTop.init();

        $(window).on('scrollstart', function() {
			$('body').addClass('scrolling');
		  	//console.log("scrollstart");

			// exécuter la détection du projet visible toutes les 1000ms, sauf si c'est un scroll automatique
			if ($viewport.is(':animated')) {
				console.log( "animated");
				return;
			}

			// détection de la proximité d'article à la volée, en plein scroll => très lourd en ressources...
			detectArticle();
        });

		$(window).on('scroll', function () {
			// placer le backtotop en vu
			backToTop.recalc(window.pageYOffset, newArticleVu);
		});

        $(window).on('scrollstop', function() {

			$('body').removeClass('scrolling');

		    // quand le scroll est finis depuis plus de 500 ms (se déclenche une fois)
		    // ajuster la hauteur entre header et projets
    		adjustHeight();
    		mapScroll.remap();

			// supprimer la détection d'article vu
			clearTimeout(restartDetectArticle);

			// placer le backtotop en vu
			backToTop.init();
			backToTop.recalc(window.pageYOffset, activeArticle(window.pageYOffset) );

		   // on annule le scroll vers un projet spécifique dans ce cas
		   scrollOnReady = '';

		   // scroll infini : de retour en haut quand on arrive en bas
			//console.log("$(window).scrollTop() : " + $(window).scrollTop());
			//console.log("$('body').height() : " + $('body').height());
			//console.log("$(window).height() : " + $(window).height());

	        /*
if ( $(window).scrollTop() >= ($('body').height() - $(window).height() + window.articleMarginTop) ) {
	            $(window).scrollTop(1);
	        }
	        else if ( $(window).scrollTop() == 0 ) {
	            $(window).scrollTop($('body').height() - $(window).height() + window.articleMarginTop -1);
	        }
			*/
	    });


		// à la fin d'un resize
	    $(window).resize(function () {
		    waitForFinalEvent(function(){
			    // recalculer la hauteur entre les images
				//console.log('resize-end');
				adjustHeight();
/* 				mapScroll.remap(); */
				backToTop.init();
				canvasConnexe.resize();
				activeArticle(window.pageYOffset);
		    }, 500, "resize");
		});


		// hotkeys ! Pour la navigation dans les projets et les images
	    $(document).bind('keydown', 'down',function (evt){
	        navPage.imageSuiv();
	        return false;
	    });
	    $(document).bind('keydown', 'up',function (evt){
	        navPage.imagePrec();
	        return false;
	    });
	    $(document).bind('keydown', 'right',function (evt){
			navPage.projetSuiv();
			return false;
		});
	    $(document).bind('keydown', 'left',function (evt){
			navPage.projetPrec();
			return false;
		});

		// basculer de mode en appuyant sur 'n'
		$(document).bind('keydown', 'alt', function (evt){
			$('body').toggleClass('jour');
		});

		// on met tous les articles en périphérie
		$('article.article').addClass('peripherie');
		// on calcule le projet le plus proche
		articleVu = articleProche(window.pageYOffset + articleMarginTop);

		// et au chargement de la page, on scan l'url pour voir si il y a un article à montrer en particulier
		// marche grâce aux id, même si pas super clean. À voir la compatibilité avec tous les devices si géré en JS


		// le jquery scroll se stop au click ou au scroll manuel // de stackoverflow
	    $viewport.bind("scroll mousedown DOMMouseScroll mousewheel", function(e){
		    if ( e.which > 0 || e.type === "mousedown" || e.type === "mousewheel"){
		         $viewport.stop();
	 	    }
		});

		// slide vers l'article au click
	    $('#lienProjets a:not(.disabled), .backtotop a, .titreProj').click(function (e) {
	        e.preventDefault();
	        var dataslide = $(this).data('projet');
	        scrollOnReady = '';
			gotoByScroll(dataslide);
	        console.log("scroll vers : " + dataslide);
	    });
		// au click sur un lienprojet
		$('#lienProjets a:not(.disabled)')
			.mousedown( function (e) {
				console.log("mousedown)");
				$(this).addClass("pushed");
			})
			.mouseup(function (e) {
				console.log("mouseup)");
				$(this).removeClass("pushed");
			})
			.mouseout(function (e) {
				console.log("mouseout)");
				$(this).removeClass("pushed");
			});

	    $('#backtotopglobal>a').click(function (e) {
	    	e.preventDefault();
		    $viewport.animate({
		        scrollTop: 0
		    }, 900, 'easeInOutQuint');
	    });

	    $('#cv .header, #navigation .collapser').click(function (e) {

	    	$('#cv .status-witness').toggleClass("open");
	    	var openornot = $('#cv .status-witness').hasClass("open");
			if ( openornot ) {
				$('#cv .collapsable').each(function() {
					$this = $(this);
	 				$this.removeClass("collapsed");
				});
				$('main').css('opacity', '0');
				$('main').css('marginTop', '1280px');
				waitForFinalEvent(	function () {
					$('main').css('display', 'none');
				    $viewport.animate({
					        scrollTop: 0
					    }, 0, 'easeInOutQuint', function () {}
				    );
     				$("body").addClass("navbarouverte");
				}, 400, "removeAndRelative");

			} else {
				$('#cv .collapsable').each(function() {
 					$this = $(this);
	 				$this.addClass("collapsed");
				});
				$('main').css('display', 'block');
				$('main').css('marginTop', '0px');
			    $viewport.animate({
				        scrollTop: 0
				    }, 200, 'easeInOutQuint', function () {}
			    );
 				$("body").removeClass("navbarouverte");
 				waitForFinalEvent( function () {
 					adjustHeight();
					$('main').css('opacity', '1');
 				}, 1200, "readjustheight");
			}

/* 		    $(".article").css( 'marginTop', 1000 + 'px'); */
/* 			var readjust = setTimeout(adjustHeight, 1200); */



	    });

	});

	$(window).load(function() {

		// a la fin de chargement complet du doc, ajuster l'espace pour la navbar en haut et entre les projets
		adjustHeight();
/* 		mapScroll.remap(); */
		$('picture').css('opacity', 1);
		$('.loader').css('opacity', 0);

		setTimeout(function () {
			if (scrollOnReady) {
				console.log("scrollOnReady : " + scrollOnReady);
				gotoByScroll(scrollOnReady);
			}
		}, 550);


	});

	/* background animation for Connexe */
	/* made with PJS, only for >1024px devices, (ipad and desktop) */
	Modernizr.load({
	    test: Modernizr.touch,
	    yep : '',
	    nope : '//cdnjs.cloudflare.com/ajax/libs/processing.js/1.4.1/processing.min.js',
	    callback : function (url, result, key) {
	    	if (!result)
		    	canvasConnexe.init();
	    }
	});

	var canvasConnexe = {
		init : function() {
			// trouver le canvas dans la page
			var canvas = $(".cnx canvas");
			// rattacher le sketch au doc HTML
			canvas.attr('data-processing-sources', "js/balls.pde");
			canvas.css('visibility',"visible");

			// recharger le canvas
			  var source = canvas.attr("data-processing-sources");
			  if (source !== undefined) {
			    var sourceFiles = source.split(/\s+/);
			    Processing.loadSketchFromSources("balls", sourceFiles);
			  }



			$('canvas#balls').hover(
				function() {
					//console.log("hover");
					Processing.getInstanceById('balls').loop();
				}, function() {
					//console.log("nohover");
					Processing.getInstanceById('balls').noLoop();
				}
			);

		},

		resize : function () {
			// recalculer la largeur du sketch Connexe si il existe bien
			if ($('.cnx canvas').attr("data-processing-sources") !== undefined)
				Processing.getInstanceById('balls').updateSize($('.cnx.article .imgfond').width());
		}

	};

	var navPage = {

		imageSuiv : function() {

			var imgToScroll = $('picture').eq(imgActuelle);

		    $viewport.animate({
			        scrollTop: imgToScroll.offset().top
			    }, 300, 'easeInOutQuint', function () {}
		    );
			imgActuelle++;
		},

		imagePrec : function () {
			if (imgActuelle > 0) {
				imgActuelle--;
				var imgToScroll = $('picture').eq(imgActuelle);
			    $viewport.animate({
				        scrollTop: imgToScroll.offset().top
				    }, 300, 'easeInOutQuint', function () {}
			    );
			}
			else {
			    $viewport.animate({
				        scrollTop: 0
				    }, 300, 'easeInOutQuint', function () {}
			    );
			}
		},

		projetSuiv : function() {

			// si hash il y a
			if (window.location.hash !== '' && window.location.hash !== '#') {
				// on récupère le nom du projet sur lequel on est, grace au hash
				var projetVuActuellement = window.location.hash.substr(1);
				// on récupère sa position dans l'index
				var indexProjetVuActuellement = $('article').index($('article[data-id='+projetVuActuellement+']'));
				// et on y scroll en passant son data-id à gotoByScroll
				gotoByScroll($('article').eq(indexProjetVuActuellement+1).attr('data-id'));
			}
			else {
				gotoByScroll($('article').eq(1).attr('data-id'));
			}
		},

		projetPrec : function() {
			// si hash il y a
			if (window.location.hash !== '' && window.location.hash !== '#') {
				// on récupère le nom du projet sur lequel on est, grace au hash
				var projetVuActuellement = window.location.hash.substr(1);
				// on récupère sa position dans l'index
				var indexProjetVuActuellement = $('article').index($('article[data-id='+projetVuActuellement+']'));
				// et on y scroll en passant son data-id à gotoByScroll
				gotoByScroll($('article').eq(indexProjetVuActuellement-1).attr('data-id'));
			}
			else {
				gotoByScroll($('article').eq(0).attr('data-id'));
			}
		}


	};

	/* universal delayer from stackoverflow */
	var waitForFinalEvent = (function () {
	  var timers = {};
	  return function (callback, ms, uniqueId) {
	    if (!uniqueId) {
	      uniqueId = "Don't call this twice without a uniqueId";
	    }
	    if (timers[uniqueId]) {
	      clearTimeout (timers[uniqueId]);
	    }
	    timers[uniqueId] = setTimeout(callback, ms);
	  };
	})();

	// ajuster la hauteur de margin-top
	adjustHeight = function () {
		if ( $(window).width() > 991 ) {
		    var height = $('#navbar').height() + 30 ;
		    $(".article").css( 'marginTop', height + 'px');
		    window.articleMarginTop = height;
		}
	};

	var mapScroll = {

		init : function() {
			$( "#backtotopglobal" ).after( "<nav id='scrollmapcontainer'></nav>" );
			$("article.article").each( function () {
				$( "#scrollmapcontainer" ).append("<div class='reperes'><div class='titre'><h4>" + $(this).find(".titreProj h2").text() + "</h4></div></div>");
			});
		},

		remap : function() {
			if ( $(window).width() > 991 ) {
				//console.log("remap");

				var docheight = $(document).height();
				var marginglobal = window.articleMarginTop / 50;
				var scrollmcheight = $( "#scrollmapcontainer" ).height();

				// div = hauteur des projets
				$("article.article").each( function (index) {
/* 					console.log( "data-id = " + $(this).attr('data-id') + " $(this).height() : " + $(this).height() + " $(this).innerHeight() : " + $(this).innerHeight() + " this.offsetHeight : " + this.offsetHeight ); */
					var maptoptoscrollmap = ( $(this).offset().top / docheight ) * scrollmcheight;
					var mapheighttoscrollmap = ( $(this).innerHeight() / docheight ) * scrollmcheight;
					$( "#scrollmapcontainer .reperes" ).eq(index).css('height', mapheighttoscrollmap).css('top', maptoptoscrollmap);
				});

/*
				$("article.article").each( function (index) {
					var maptoptoscrollmap = ( $(this).offset().top / docheight ) * scrollmcheight;
					var mapheighttoscrollmap = ( $(this).innerHeight() / docheight ) * scrollmcheight;
					$( "#scrollmapcontainer .reperes" ).eq(index).css('top', maptoptoscrollmap );
				});
*/

			}
		}
	};

	// fonction scroll vers le projet au click dans le header ou au clavier
	gotoByScroll = function (idProjet) {
		var leProjet = $(".article").filter(function(){ return $(this).attr('data-id').match(idProjet) });
		//console.log("gotoByScroll vers : " + leProjet.attr('class') + " window.articleMarginTop : " + window.articleMarginTop);
		adjustHeight();
		if ($(window).width() <= 991) window.articleMarginTop = 0;
	    $viewport.animate({
		        scrollTop: leProjet.offset().top - window.articleMarginTop
		    }, 900, 'easeInOutQuint');
	}

	// mettre en active le lien du menu correspondant au projet actuellement vu
	activeLien = function (lienProjet) {
		console.log(lienProjet);
		$('#lienProjets a').removeClass('active').filter(function() {
			return $(this).data('projet') === lienProjet;
		}).addClass('active');
	}

	articleProche = function (modwscrollTop) {
		var dist =0;
		var pDist=10000000000;
		var articleActif;
		//optimisation : stocker le numéro d'article plutôt que l'article : http://jsperf.com/jquery-each-this-vs-eq-index
		var numArticleActif;

		var $articles = $('article.article');
		$articles.each( function(index){
			dist = Math.abs(modwscrollTop - this.offsetTop);
			if(dist<pDist) {
				pDist = dist;
				numArticleActif = index;
			}
			dist = Math.abs(modwscrollTop - (this.offsetTop + this.offsetHeight));
			if(dist<pDist) {
				pDist = dist;
				numArticleActif = index;
			}
		});
		articleActif = $articles.eq(numArticleActif);
		return articleActif;
	}

	var backToTop = {

		init : function() {

			if ($(window).width() > 991) {

				var margeGaucheArticle = $("article.article .row:first-child").offset().left;

				$("article.article").each(function() {
					var titreProj = $(this).find(".titreProj");
					titreProj.removeClass().addClass("titreProj");
					titreProj.data('projet', $(this).find(".anchorLink").data("projet"));

					var articledataid =  $(this).data("id");
					var lienProjHeader = $('#lienProjets a').filter(function() {
						return $(this).data('projet') === articledataid;
					}).parent(".column");

					var moveLeft = lienProjHeader.offset().left - margeGaucheArticle;
					var newwidth = lienProjHeader.outerWidth();
					titreProj.find(".boiteProj").css({
						"left" : moveLeft,
						"width" : newwidth
					});
				});
			} else {
				$("article.article").each(function() {
					var titreProj = $(this).find(".titreProj");
					titreProj.removeClass().addClass("titreProj col-md-2");
				});
			}

		},

		recalc : function(scrollFromTop, articleActifAct) {
			//console.log("backToTop");
			var $articleActifAct = $(articleActifAct);
			var scrollBackToTop = scrollFromTop - $articleActifAct.offset().top;

	/*
			if (scrollBackToTop <= 0)
				scrollBackToTop = 0;

			scrollBackToTop = scrollBackToTop > $articleActifAct.height()-80 ? $articleActifAct.height()-80 : scrollBackToTop;

			$articleActifAct.find(".backtotopicon").css( 'top', scrollBackToTop);
			//console.log("scrollFromTop : " + scrollBackToTop + " theoffset.height : " + theoffset.height);
	*/

			if ($(window).width() > 991) {
				if (scrollBackToTop <= 0 || scrollBackToTop >= $articleActifAct.height()-80) {
					$articleActifAct.find(".backtotopicon").removeClass("sticky");
					$articleActifAct.find(".titreProj").removeClass("sticky");
				}
				else {
					$(".backtotopicon").removeClass("sticky");
					$articleActifAct.find(".backtotopicon").addClass("sticky");
					$articleActifAct.find(".titreProj").addClass("sticky");
				}
			} else {
				if ( scrollFromTop < $(window).height() ) {
					$("#backtotopglobal").css('opacity', '0');
				} else {
					$("#backtotopglobal").css('opacity', '1');
				}
			}

		}
	};

	detectArticle = function () {
		activeArticle(window.pageYOffset);
		//console.log("detectArticle");
	    restartDetectArticle = setTimeout(detectArticle, 1000);
	};

	activeArticle = function (wscrollTop) {

/* 		var start = new Date().getTime(); */

    	// on trouve l'article le plus proche (vu principalement)
		newArticleVu = articleProche(wscrollTop + articleMarginTop);

		//console.log("activeArticle");

		// comparer à l'article vu (optimisation)
		if ( newArticleVu.hasClass("peripherie") ) {

			$("article.article").addClass("peripherie");
			newArticleVu.removeClass("peripherie");

			articleVu = newArticleVu;

			// charger les images si on passe sur un projet qui en contient, en pleine résolution si nécessaire
			articleVu.find('picture:not(.video)').picture({ container:'.imgfond', inlineDimensions:true, forceSmall:false });

			// on récupère le data-id de l'article
			var inviewId = articleVu.attr('data-id');

			// si pas d'id trouvé, on s'arrête là
			if (inviewId === undefined) return;

			// ajouter le nom du projet à l'url
			window.location.hash = inviewId;

			activeLien(inviewId);
		}

		return newArticleVu;
/*
		var end = new Date().getTime();
		var time = end - start;
		console.log('Execution time: ' + time);
*/

	};




  });

}).call(this);


