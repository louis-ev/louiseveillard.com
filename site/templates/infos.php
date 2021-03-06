<?php snippet('header') ?>
<?php snippet('menu') ?>

<main id="pjax-container" class="main" role="main">
  <?= pjaxInfos( $page); ?>

	<div class="module--infos">
		<header class="module--infos--textblock">
	  	<?php echo $page->text()->kirbytext() ?>
	  </header>
	</div>

	<section class="module--cv pjax">
<!--
	  <div class="langs">
  		  <a href="<?= $page->url(); ?>/lang:en">
    		  English
  		  </a>
  		  <a href="<?= $page->url(); ?>/lang:fr">
  		    Français
  		  </a>
	  </div>
-->

<?php
if(param('lang') !== 'en'):
?>

<?php
  $years = array(2019, 2018, 2017, 2016, 2015, 2014, 2013, 2012, 2011, 2010);
?>

  <?php foreach($years as $year): ?>
    <?php if($page->content()->has('activities' . $year) && $page->content()->get('activities' . $year)->isNotEmpty()): ?>
    <div class="m_detail">
      <div class="m_detail--item">
        <div class="header">
          <h3><?= $year ?></h3>
        </div>
        <div class="description">
          <ul>
          <?php foreach($page->content()->get('activities' . $year)->toStructure() as $detail): ?>
            <li><?= $detail->text()->kirbytext(); ?></li>
          <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <?php endif; ?>
  <?php endforeach; ?>


<?php /*
    <div class="m_detail">
      <?php foreach($page->detail()->toStructure() as $detail): ?>
        <div class="m_detail--item">
          <div class="header">
            <h3><?= $detail->intitule(); ?></h3>
          </div>
          <div class="description">
            <?= $detail->text()->kirbytext(); ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
*/ ?>

<?php
else:
?>

<?php /*
    <div class="m_detail">
      <?php foreach($page->detailEN()->toStructure() as $detail): ?>
        <div class="m_detail--item">
          <div class="header">
            <h3><?= $detail->intitule(); ?></h3>
          </div>
          <div class="description">
            <?= $detail->text()->kirbytext(); ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
*/ ?>

<?php
  endif;
?>
<?php

  return;
  /*
if(param('lang') !== 'en'):
?>
		<div id="details" data-lang="fr" class="ui three column stackable grid">
<!-- 				<div class="row"> -->
				<div class="column ">
				    <div class="content activites">
							<div class="header">
				    	    	<h3>Activités</h3>
							</div>
							<ul class="description">
				    			<li>Designer indépendant à Paris.</li>
								<li>Membre fondateur du collectif <a data-link='latelier' href="http://www.latelier-des-chercheurs.fr" target="_blank">l’Atelier des Chercheurs</a>.</li>
						    	<li>Co-fondateur de <a data-link="panopticlab" href="http://panoptic-lab.com/" target="_blank">Panoptic Lab</a>, un collectif œuvrant dans la réalité virtuelle.</li>
							</ul>
				    </div>
		    	</div>

				<div class="column ">
				    <div class="content realisations">
							<div class="header">
				    	    	<h3>Réalisations web</h3>
							</div>
							<ul class="description">
  							  <li>
  									<a href="https://acrobatesbuilder.fr/en" target="_blank">acrobatesbuilder.fr/en</a> <span class="realisationsDetails">avec <a href="http://vdesclaux.com/" target="_blank">Vincent Desclaux</a></span>
                </li>
  							  <li>
  									<a href="https://ante-prima.com/" target="_blank">ante-prima.com</a> <span class="realisationsDetails">avec <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a></span>
  							  </li>
  							  <li>
  									<a href="https://thuvantran.fr/" target="_blank">thuvantran.fr</a> <span class="realisationsDetails">avec <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a></span>
  							  </li>
  							  <li>
  									<a href="https://www.studiotheatre.fr/" target="_blank">studiotheatre.fr</a> <span class="realisationsDetails">avec <a href="http://vdesclaux.com/" target="_blank">Vincent Desclaux</a></span>
  							  </li>
  							  <li>
  									<a href="http://www.madd-bordeaux.fr/" target="_blank">madd-bordeaux.fr</a> <span class="realisationsDetails">maquettes uniquement, avec <a href="https://sarahgarcin.com/" target="_blank">Sarah Garcin</a></span>
  							  </li>
  							  <li>
  									<a href="http://www.rhplus-architecture.com/" target="_blank">rhplus-architecture.com</a>
  							  </li>
								<li>
									<a href="http://delure.org/" data-link='delure' target="_blank">delure.org</a> <span class="realisationsDetails">avec la participation d’<a href="http://www.arnaudjuracek.fr/" target="_blank">Arnaud Juracek</a></span>
								</li>
								<li>
									<a href="http://www.baldingervuhuu.com/" target="_blank">baldingervuhuu.com</a> <span class="realisationsDetails">avec <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a></span>
								</li>
								<li>
									<a href="http://frenak-jullien.com/" target="_blank">frenak-jullien.com</a> <span class="realisationsDetails">avec <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a></span>
								</li>
								<li>
									<a href="http://www.fraciledefrance.com/" target="_blank">fraciledefrance.com</a> <span class="realisationsDetails">avec <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a></span>
								</li>
							</ul>
				    </div>
		    	</div>

				<div class="column ">
				    <div class="content interventions">
						<div class="header">
			    	    	<h3>Interventions</h3>
						</div>
						<ul class="description">
							<li>Workshop de 5 jours sur la création <em>d’affiches interactives sur le web</em> à l’<a href="http://www.esapyrenees.fr/" target="_blank">École supérieure d’art des Pyrénées</a> du 18 au 22 décembre 2017. Compte-rendu, références, et projets des étudiants <a href="https://affichesinteractives.louiseveillard.com/" target="_blank">sur cette page</a>.</li>
							<li>Intervention sur la data-physicalisation à l’occasion d’une journée d’étude sur le sujet organisé par <a href="https://www.univ-rennes2.fr/" target="_blank">l’université Rennes 2</a> (2017).</li>
							<li>Conférence de présentation de mon travail et de mes recherches à <a href="http://www.esad-orleans.fr/" target="_blank">l’ÉSAD d’Orléans</a> (2017).</li>
							<li>Intervention théorique et pratique autour de la représentation de données avec les étudiants du BTS Design Graphique au <a href="http://www.lyceevernant.fr/dg/" target="_blank">Lycée Vernant</a>, à Sèvres (2017).</li>
  						  <li>Enseignant en webdesign dans le cycle de <em>Communication Design</em> au <a href="https://www.paris.edu/" target="_blank">Paris College of Art</a> (2017).</li>
							<li>Workshop sur le thème de la <em>data-physicalisation</em> à l’<a href="https://www.lecolededesign.com/" target="_blank">École de design Nantes Atlantique</a> du 15 au 19 mai 2017. Compte-rendu, références, méthodologie et projets des étudiants <a href="https://datafossil.xyz/" target="_blank">sur cette page</a>.</li>
							<li>Conférence de présentation de mon travail avec des robots et des programmes dans le cadre du club tech'ART au Lycée Louis Armand dans le XV<sup>e</sup> arrondissement de Paris (2017).</li>
							<li>Conférence de présentation de mon travail et de mes recherches au <a href="https://www.campusfonderiedelimage.org/" target="_blank">Campus Fonderie de l’Image</a> (2017).</li>
							<li>Intervention à l’<a href="www.ecole-boulle.org/articles/4171/conference-culture-code" target="_blank">école Boulle</a> lors d’une conférence sur le sujet <em>Culture Code</em> (2017).</li>
							<li>Intervention à la table-ronde du <a href="http://www.stereolux.org/agenda/table-ronde-la-materialite-des-donnees" target="_blank">festival Scopitone</a> à Nantes sur la <em>Matérialité des Données</em> (2016).</li>
							<li>Conférence aux <a href="http://delure.org/" target="_blank">Rencontres internationales de Lure</a> sur le thème <em>Chercher l’erreur – Égarements et odyssées graphiques</em> (2016).</li>
							<li>Conférence à l’<a href="http://www.ensci.com/" target="_blank">ENSCI</a> pour la journée d’étude <em>La Fabrique des Outils</em> organisée par l'association <a href="http://www.designenrecherche.org/evenements/la-fabrique-des-outils" target="_blank">Design en Recherche</a> (2016).</li>
							<li>Conférence à l’<a href="http://www.ensad.fr/" target="_blank">ENSAD</a> pour la présentation du projet de création et de réalisation de 10000 couvertures pour livres numériques (2016).</li>
							<li>Conférence aux <a href="http://delure.org/les-a-cotes/evenements-et-workshops/mardis-de-lure-1" target="_blank" data-link="delure">Mardis de Lure</a> à la Générale à Paris pour présenter des outils pour enseigner le design et encourager des pédagogies actives (2015).</li>
							<li>Workshop à la <a href="http://www.newschool.edu/parsons-paris/" target="_blank">PARSONS</a> à Paris dans le cours <em>Creativity and Computation Lab</em> (2015).</li>
							<li>Workshop à la conférence <a href="http://learnxdesign2015.com/" target="_blank">LearnxDesign 2015</a> à Chicago, avec l’équipe de <a data-link="latelier" href="http://www.latelier-des-chercheurs.fr" target="_blank">l’Atelier des Chercheurs</a> (2015).</li>
							<li>Conférence de présentation de mon travail et de mes recherches à l’<a href="http://www.penninghen.fr/" target="_blank">ESAG Penninghen</a> (2015).</li>
							<li>Introduction au <a data-link='tricodeur' href="http://www.letricodeur.com">Tricodeur</a> lors de la <a href="http://www.dailymotion.com/video/x2mhyja_louis-eveillard-soiree-di-zain-21-mode-s_creation" target="_blank">soirée *di*zaïn #21</a>, au Grand Palais à Paris le 11 avril 2015.</li>
							<li>Présentation de <a href="http://panoptic-lab.com/" data-link="panopticlab">Panoptic Lab</a> au talk sur l’art génératif organisé par <a href="https://brightfor.me/">Bright</a> au <a href="http://silencio-club.com/fr" target="_blank">Silencio</a>, à Paris, le 19 février 2015.</li>
							<li>Workshop et conférence sur la matérialisation du numérique par le code à l’<a href="http://www.esalorraine.fr/metz/" target="_blank">ésal Metz</a> les 2, 3 et 4 février 2015.</li>
							<li>Conférence publique sur la représentation des traces numériques à l’<a data-link='esad-amiens' href="http://www.esad-amiens.fr/" target="_blank">ésad d’Amiens</a> le 9 décembre 2014.</li>
							<li>Workshop dans le cadre du <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a> sur le détournement et l’utilisation de machines à tricoter programmables pour réaliser des motifs générés par des données personnelles. Du 10 au 12 octobre 2014 à Cap Science à Bordeaux, en collaboration avec l’association <a href="http://www.sewetlaine.com/" target="_blank">Sew&Laine</a> et <a href="http://www.2roqs.fr/" target="_blank">Studio 2Roqs</a>).</li>
							<li>Enseignant de design génératif en 4<sup>e</sup> année à <a href='http://www.hetic.net/' target='_blank'>HETIC</a> à Montreuil (2013). Résumé et code du cours <a href="http://www.beautifulseams.com/2013/10/31/teaching-generative-design/" data-link='blog' target="_blank">sur mon blog</a>.</li>
							<li>Conférence sur l’algorithme dans l’art et le design à l’<a href="http://www.penninghen.fr/" target="_blank">ESAG Penninghen</a> (2013). Compte-rendu, slides et projets des étudiants <a data-link='algorithme' href="http://algorithme.beautifulseams.com/" target="_blank">sur cette page</a>.</li>
						</ul>
				    </div>
		    	</div>

		    	<div class="column ">
				    <div class="content prix">
						<div class="header">
							<h3>Prix & diplômes</h3>
						</div>
						<ul class="description">
							<li>Or au concours <a href="http://vrjam.challengepost.com/submissions" target="_blank">Oculus VR jam 2015</a> pour l’application <a href="http://panoptic-lab.com/alongthetrail/" data-link='panopticlab' target="_blank">Along the Trail</a> (2015).</li>
							<li>Argent au concours <a href="http://vrjam.challengepost.com/submissions" target="_blank">Oculus VR jam 2015</a> pour le jeu <a href="http://panoptic-lab.com/panopticon/" data-link='panopticlab' target="_blank">Panopticon</a> (2015).</li>
							<li>BronzeAward avec Habsense aux <a href="http://www.samsung.com/fr/designedfor/" target="_blank">Samsung Design Awards</a> (2014).</li>
							<li>Prix spécial du jury avec <a data-link='connexe' href="http://www.connexe.org" target="_blank">Connexe</a> au hackathon <a href="http://www.dailymotion.com/video/x16ywlv_l-automne-numerique-remise-des-prix_news" target="_blank">dataculture</a> du Ministère de la Culture et de la Communication (2013).</li>
							<li>Prix d’exposition avec Breakpoint au concours <a href="http://urbandatachallenge.org" target="_blank">Urban Data Challenge</a> (2013).</li>
							<li>Diplômé du DNSEP (grade Master) en design graphique avec les félicitations du jury à l’<a data-link='esad-amiens' href="http://www.esad-amiens.fr/" target="_blank">ésad d’Amiens</a> (2012).</li>
							<li>Diplômé du DNAP avec les félicitations du jury à l’<a data-link='esad-amiens' href="http://www.esad-amiens.fr/" target="_blank">ésad d’Amiens</a> (2010).</li>
						</ul>
				    </div>
		    	</div>
		    	<div class="column ">
				    <div class="content publications">
							<div class="header">
								<h3>Publications</h3>
							</div>
							<ul class="description">
  							  <li>Article récapitulatif du projet Acrobates Builder sur Medium, écris avec <a href="http://vdesclaux.com/" target="_blank">Vincent Desclaux</a> : <a href="https://medium.com/@timetofirstfix/designing-and-building-a-web-app-from-scratch-a-case-study-39e587951f41" target="_blank">Designing and building a web app from scratch: a case study</a>.</li>
								<li><a href="https://twitter.com/timetofirstfix/status/923184802781638656" target="_blank">étapes: #239</a> (septembre – octobre 2017) – dossier <em>Art, design & algorithme</em>.</li>
								<li><a href="http://etapes.com/etapes-237" target="_blank">étapes: #237</a> (mai 2017) – présentation du <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a> et de Acrobates Builder.</li>
								<li><a href="http://www.revue-backoffice.com/" target="_blank">Back Office</a>, éditions Fork & B42 (2017) – présentation du <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a> dans l’article Œuvrer avec les machines numériques de Sophie Fétro.</li>
								<li><a href="http://www.r-diffusion.org/index.php?ouvrage=CAM-09" target="_blank">Algorithmes typographiques</a>, éditions La clé à molette (2016) – présentation d'un générateur aléatoire de caractères.</li>
								<li><a href="http://www.eyrolles.com/Audiovisuel/Livre/fibres-fils-tissus-9782212143126" target="_blank">Fibres, fils, tissus</a>, éditions Eyrolles (2016) – introduction au <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a>.</li>
								<li>Gourlet, P., Dervieux, F., Eveillard, L., Mancini, J. (2015) 'The Research Diary: Documenting and Publishing Classrooms' Activites', in International Journal for Cross-Disciplinary Subjects in Education, Volume 6, Issue 1.</li>
								<li>Gourlet, P., Dervieux, F., Eveillard, L., Garcin, S. (2015) 'Designing learning tools for education', workshop DRS/LearnxDesign 2015, Chicago, USA.</li>
								<li><a href="./content/2-infos/bergere-de-france-inspire-tricodeur-2.jpg" target="_blank">inspire&vous</a>, le magazine de Bergère de France (2014) – présentation du <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a>.</li>
								<li><a href="http://www.pearson.fr/livre/?GCOI=27440100443180" target="_blank">Projets créatifs avec Arduino</a> (2014) – présentation de <a href="./projets/slowscreen" data-link='slowscreen'>Slowscreen</a>.</li>
								<li><a href="./content/2-infos/hab-intramuros-172.jpg" target="_blank">Intramuros #172</a> (mai-juin 2014) – publication du projet Habsense.</li>
								<li><a href="http://www.mouvement.net/analyses/enquetes/faciliter-lacces-au-web" target="_blank">Mouvement #72</a> (janvier-février 2014) – présentation du projet Breakpoint.</li>
								<li>étapes: #216 (octobre 2013) – présentation de <a href="./projets/slowscreen" data-link='slowscreen'>Slowscreen</a>.</li>
								<li>étapes: #209 (octobre 2012) – présentation de Hypomnémata.</li>
							</ul>
				    </div>
		    	</div>
		    	<div class="column ">
				    <div class="content expositions">
							<div class="header">
								<h3>Expositions</h3>
							</div>
							<ul class="description">
								<li>Présentation du <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a> à la Semaine Digitale, Hotel de Ville de Bordeaux (2014)</li>
								<li>Présentation du livre Arsène Lupin à l'exposition <a href="http://chercherletexte.org/fr/" target="_blank">Chercher le Texte</a>, BNF, Paris (2013)</li>
								<li>Présentation du projet Lis-moi, Lis-moi au Salon du livre, Paris (2013)</li>
								<li>Exposition collective – Amnesia/Memoria, Marke.06, Weimar, Allemagne (2010)</li>
								<li>Exposition au Nooka Show, Red Bull Space, New York, États-Unis (2010)</li>
							</ul>
				    </div>
		    	</div>
				</div>

<!--                                      // english -->
<?php
else:

?>

		<div id="details" data-lang="en" class="ui three column stackable grid">

			<div class="row">
				<div class="column collapsable is-collapsed">
				    <div class="content activites">
							<div class="header">
				    	    	<h3>Activities</h3>
							</div>
							<ul class="description">
				    		<li>Freelance designer in Paris.</li>
							<li>Member of the group <a data-link='latelier' href="http://www.latelier-des-chercheurs.fr" target="_blank">l'atelier des chercheurs</a>.</li>
  					    	<li>Co-founder of <a data-link="panopticlab" href="http://panoptic-lab.com/" target="_blank">Panoptic Lab</a>, a design studio working on Virtual Reality projects.</li>
					    <li>Co-organizer of events and sessions for the <a href='http://freeartbureau.org/sundays/' data-link='fab' target='_blank'>Free Art Sundays</a> at the <a href='http://freeartbureau.org/' data-link='fab' target='_blank'>Free Art Bureau</a>, a not-for-profit association dedicated to promoting open-source digital tools for designers and artists.</li>
							</ul>
				    </div>
		    	</div>

<!--

	    	sites sur lesquels j'ai travaillé :
		    	http://chevalvert.fr/ (animations, responsive, typographie, navigation, code)
		    	http://newsoftheartworld.com/ (responsive, agenda culturel dynamique, code)
		    	http://www.fraciledefrance.com/ (intégration de l'identité et des maquettes, responsive, navigation, code)
		    	http://freeartbureau.org/sundays/ (maquette, responsive, navigation, code)
		    	http://letricodeur.com/ (maquette, contenus, typographie, code)
		    	http://connexe.org/ (maquette (en collaboration avec l'équipe du projet Connexe), typographie, animations, code)
		    	http://sociablemedia.ensadlab.fr/ (maquette (avec Ferdinand Dervieux et Max Mollon), typographie, navigation, code)
		    	http://www.beautifulseams.com/ (maquette, navigation, animations, code)
		    	http://letricodeur.com/workshop (maquette, contenus, typographie, code)
		    	http://algorithme.beautifulseams.com/ (maquette, contenus, typographie, code)
		    	http://www.mfpp-origami.fr/ (maquette, typographie, responsive, navigation, code)
		    	http://latelier-des-chercheurs.fr/ (maquette (avec Juliette Mancini et Pauline Gourlet), navigation, responsive, code)
		    	http://www.emiliecoquard.fr/ (maquette (avec Émilie Coquard), navigation, animation, responsive, code)

-->

				<div class="column collapsable is-collapsed">
				    <div class="content realisations">
							<div class="header">
				    	    	<h3>Websites</h3>
							</div>
							<ul class="description">
								<li>
									<a href="https://thuvantran.fr/" target="_blank">thuvantran.fr</a> <span class="realisationsDetails">with <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a></span>
								</li>
								<li>
									<a href="http://delure.org/" data-link='delure' target="_blank">delure.org</a> <span class="realisationsDetails">identity design, mockups, development, responsive</span>
								</li>
								<li>
									<a href="http://www.baldingervuhuu.com/" target="_blank">baldingervuhuu.com</a> <span class="realisationsDetails">development (mockups by <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a>), animations, responsive, code</span>
								</li>
								<li>
									<a href="http://frenak-jullien.com/" target="_blank">frenak-jullien.com</a> <span class="realisationsDetails">integration (mockups by <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a>), responsive, code, admin</span>
								</li>
								<li>
									<a href="http://panoptic-lab.com/" data-link="panopticlab" target="_blank">panoptic-lab.com</a> <span class="realisationsDetails">mockups, generative animation, responsive, code</span>
								</li>
								<li>
									<a href="http://www.emiliecoquard.fr/" target="_blank">emiliecoquard.fr</a> <span class="realisationsDetails">mockups (with Émilie Coquard), navigation, animation, responsive, code</span>
								</li>
								<li>
									<a data-link='chevalvert' href="http://www.newsoftheartworld.com/" target="_blank">newsoftheartworld.com</a> <span class="realisationsDetails">making and integration (mockups by <a href="http://chevalvert.fr/" target="_blank">Chevalvert</a>), responsive, events calendar, code, admin</span>
								</li>
								<li>
									<a data-link='chevalvert' href="http://www.chevalvert.fr" target="_blank">chevalvert.fr</a> <span class="realisationsDetails">responsive, animations, code</span>
								</li>
								<li>
									<a data-link='frac' href="http://www.fraciledefrance.com/" target="_blank">fraciledefrance.com</a> <span class="realisationsDetails">integration (mockups by <a href="http://www.baldingervuhuu.com/" data-link='bvh' target="_blank">Baldinger•Vu-Huu</a>), responsive, navigation, code, admin</span>
								</li>
								<li>
									<a data-link='socmed' href="http://sociablemedia.ensadlab.fr" target="_blank">sociablemedia.ensadlab.fr</a> <span class="realisationsDetails">mockups (with Ferdinand Dervieux and Max Mollon), type, navigation, code</span>
								</li>
								<li>
									<a href="http://www.mfpp-origami.fr" target="_blank">mfpp-origami.fr</a> <span class="realisationsDetails">mockups, type, responsive, navigation, code</span>
								</li>
								<li>
									<a data-link='fab' href="http://www.freeartbureau.org/sundays/" target="_blank">freeartbureau.org/sundays/</a> <span class="realisationsDetails">mockups, type, responsive, navigation, code</span>
								</li>
								<li>
									<a data-link='tricodeur' href="http://www.letricodeur.com" target="_blank">letricodeur.com</a> <span class="realisationsDetails">mockups, contents, type, code</span>
								</li>
								<li>
									<a data-link='connexe' href="http://www.connexe.org" target="_blank">connexe.org</a> <span class="realisationsDetails">mockups (in collaboration with the team), type, animations, code</span>
								</li>
								<li>
									<a data-link='blog' href="http://www.beautifulseams.com" target="_blank">beautifulseams.com</a> <span class="realisationsDetails">mockups, navigation, animations, code</span>
								</li>
								<li>
									<a data-link='algorithme' href="http://algorithme.beautifulseams.com" target="_blank">algorithme.beautifulseams.com</a> <span class="realisationsDetails">mockups, contents, type, code</span>
								</li>
								<li>
									<a data-link='tricodeur' href="http://www.letricodeur.com/workshop" target="_blank">letricodeur.com/workshop</a> <span class="realisationsDetails">mockups, contents, type, code</span>
								</li>
								<li>
									<a data-link='latelier' href="http://www.latelier-des-chercheurs.fr" target="_blank">latelier-des-chercheurs.fr</a> <span class="realisationsDetails">mockups (with Juliette Mancini and Pauline Gourlet), navigation, responsive, code</span>
								</li>
							</ul>
				    </div>
		    	</div>

				<div class="column collapsable is-collapsed">
				    <div class="content interventions">
						<div class="header">
			    	    	<h3>Interventions</h3>
						</div>
						<ul class="description">
  						  <li>Lecture on data-physicalisation at <a href="https://www.univ-rennes2.fr/" target="_blank">Université Rennes 2</a> (2017).
							<li>Lecture on my work and research at <a href="http://www.esad-orleans.fr/" target="_blank">ÉSAD Orléans</a> (2017).</li>
							<li>Lecture and workshop on data vizualisation at the Lycée Vernant, in Sèvres (2017).</li>
					    	<li>Teacher in webdesign for the BFA in <em>Communication Design</em> at the <a href="https://www.paris.edu/" target="_blank">Paris College of Art</a>.</li>
							<li>Workshop on <em>data-physicalisation</em> at l’<a href="https://www.lecolededesign.com/" target="_blank">École de design Nantes Atlantique</a> over 5 days in May 2017. Presentation, references, methods and the students' productions can be <a href="https://datafossil.xyz/" target="_blank">read on this page</a>.</li>
							<li>Lecture on my work with robots and programs at the club tech'ART of the Lycée Louis Armand in Paris (2017).</li>
							<li>Lecture on my work and research at <a href="https://www.campusfonderiedelimage.org/" target="_blank">Campus Fonderie de l’Image</a> (2017).</li>
							<li>Invited for the panel at  the <a href="www.ecole-boulle.org/articles/4171/conference-culture-code" target="_blank">école Boulle</a> to discuss the <em>Culture of Code for designers</em> (2017).</li>
							<li>Invited for the panel at  the <a href="http://www.stereolux.org/agenda/table-ronde-la-materialite-des-donnees" target="_blank">Scopitone festival 2016</a> in Nantes to discuss the <em>Materiality of Data</em> (2016).</li>
							<li>Lecture at the <a href="http://delure.org/" target="_blank">Rencontres internationales de Lure</a> on the topic of <em>Errors and failures in Graphic Design</em> (2016).</li>
							<li>Lecture at <a href="http://www.ensci.com/" target="_blank">ENSCI</a> for the seminar <em>La Fabrique des Outils</em> organized by the association <a href="http://www.designenrecherche.org/evenements/la-fabrique-des-outils" target="_blank">Design en Recherche</a> (2016).</li>
							<li>Lecture at <a href="http://www.ensad.fr/" target="_blank">ENSAD</a> on the 10.000 generated covers project for FeniXX (2016).</li>
							<li>Lecture at the <a href="http://delure.org/les-a-cotes/evenements-et-workshops/mardis-de-lure-1" target="_blank" data-link='delure'>Mardis de Lure</a> at La Générale in Paris to show and discuss tools created to teach design and support reflective practice (2015).</li>
							<li>Workshop at <a href="http://www.newschool.edu/parsons-paris/" target="_blank">PARSONS</a> in Paris in the <em>Creativity and Computation Lab</em> (2015).</li>
							<li>Workshop at LearnxDesign 2015 conference in Chicago with the team of the project <a data-link='latelier' href="http://www.latelier-des-chercheurs.fr" target="_blank">l'atelier des chercheurs</a>.</li>
							<li>Lecture on my work and research at <a href="http://www.penninghen.fr/" target="_blank">ESAG Penninghen</a> (2015).</li>
							<li>Introduction to the <a data-link='tricodeur' href="http://www.letricodeur.com">Tricodeur</a> during the <a href="http://www.dailymotion.com/video/x2mhyja_louis-eveillard-soiree-di-zain-21-mode-s_creation" target="_blank">*di*zaïn #21 event</a>, at Le Grand Palais in Paris (2015).</li>
							<li>Presentation of <a href="http://panoptic-lab.com/" data-link="panopticlab">Panoptic Lab</a> during the talk on generative art organized by <a href="https://brightfor.me/">Bright</a> at <a href="http://silencio-club.com/fr" target="_blank">Silencio club</a>, in Paris, on February 19th 2015.</li>
							<li>Workshop and lecture on the materialization of digital artefacts with code at <a href="http://www.esalorraine.fr/metz/" target="_blank">ésal Metz</a> on February 2-3-4th 2015.</li>
							<li>Public conference on the representation of online traces at <a data-link='esad-amiens' href="http://www.esad-amiens.fr/" target="_blank">ésad Amiens</a> on December 9th 2014.</li>
							<li>Residency with the association <a href="http://www.sewetlaine.com/" target="_blank">Sew&Laine</a> and <a href="http://www.2roqs.fr/" target="_blank">Studio 2Roqs</a> for the project <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">the Tricodeur</a> (September 2014). Preparation and intervention for a 3-day workshop on learning and using old knitting machines to create garments from images generated with code and online personal data.</li>
							<li>Teacher in generative design in 4<sup>th</sup> year at <a href='http://www.hetic.net/' target='_blank'>HETIC</a> in Montreuil (2013). Lessons and code on <a href="http://www.beautifulseams.com/2013/10/31/teaching-generative-design/" data-link='blog' target="_blank">my blog</a>.</li>
							<li>Conference on the use of algorithms in art and design at <a href="http://www.penninghen.fr/" target="_blank">ESAG Penninghen</a> (2013). Write-up, slides and students' projects made from an exercice I gave on <a data-link='algorithme' href="http://algorithme.beautifulseams.com/" target="_blank">this page</a>.</li>
						</ul>
				    </div>
		    	</div>
			</div>
			<div class="row">
		    	<div class="column collapsable is-collapsed">
				    <div class="content prix">
						<div class="header">
							<h3>Awards & diplomas</h3>
						</div>
						<ul class="description">
							<li>Gold prize at the <a href="http://vrjam.challengepost.com/submissions" target="_blank">Oculus VR jam 2015</a> for <a href="http://panoptic-lab.com/alongthetrail/" data-link='panopticlab' target="_blank">Along the Trail</a> (2015).</li>
							<li>Silver prize at the <a href="http://vrjam.challengepost.com/submissions" target="_blank">Oculus VR jam 2015</a> for <a href="http://panoptic-lab.com/panopticon/" data-link='panopticlab' target="_blank">Panopticon</a> (2015).</li>
							<li>Bronze award with Habsense at the <a href="http://www.samsung.com/fr/designedfor/" target="_blank">Samsung Design Awards</a> (2014).</li>
							<li>Special jury prize for <a data-link='connexe' href="http://www.connexe.org" target="_blank">Connexe</a> at the hackathon <a href="http://www.dailymotion.com/video/x16ywlv_l-automne-numerique-remise-des-prix_news" target="_blank">dataculture</a> organized by the Ministère de la Culture et de la Communication (2013).</li>
							<li>Exhibition prize with Breakpoint for the <a href="http://urbandatachallenge.org" target="_blank">Urban Data Challenge</a> (2013).</li>
							<li>Master's degree in graphic design with highest honors at <a data-link='esad-amiens' href="http://www.esad-amiens.fr/" target="_blank">ésad d'Amiens</a> (2012).</li>
							<li>Bachelor's degree in graphic design with highest honors at <a data-link='esad-amiens' href="http://www.esad-amiens.fr/" target="_blank">ésad d'Amiens</a> (2010).</li>
						</ul>
				    </div>
		    	</div>
		    	<div class="column collapsable is-collapsed">
				    <div class="content publications">
							<div class="header">
								<h3>Publications</h3>
							</div>
							<ul class="description">
<li>General overview and explanations of the project <em>Acrobates Builder</em> on Medium (written with <a href="http://vdesclaux.com/" target="_blank">Vincent Desclaux</a>): <a href="https://medium.com/@timetofirstfix/designing-and-building-a-web-app-from-scratch-a-case-study-39e587951f41" target="_blank">Designing and building a web app from scratch: a case study</a>.</li>
<li><a href="https://twitter.com/timetofirstfix/status/923184802781638656" target="_blank">étapes: #239</a> (septembre – octobre 2017) – longform on <em>Art, design & algorithme</em>.</li>
<li><a href="http://etapes.com/etapes-237" target="_blank">étapes: #237</a> (May 2017) – introduction to my profile and two projects: the <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a> and Acrobates Builder.</li>
<li><a href="http://www.revue-backoffice.com/" target="_blank">Back Office</a>, Éditions Fork & B42 (2017) – introduction to the <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a>.</li>
<li><a href="http://www.r-diffusion.org/index.php?ouvrage=CAM-09" target="_blank">Typographic algorithms</a>, éditions La clé à molette (2016) – page created by a type generator that uses random shapes and OCR.</li>
<li><a href="http://www.eyrolles.com/Audiovisuel/Livre/fibres-fils-tissus-9782212143126" target="_blank">Fibres, fils, tissus</a>, éditions Eyrolles (2016) – introduction to the <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a>.</li>
<li>Gourlet, P., Dervieux, F., Eveillard, L., Mancini, J. (2015) 'The Research Diary: Documenting and Publishing Classrooms' Activites', in International Journal for Cross-Disciplinary Subjects in Education, Volume 6, Issue 1.</li>
<li>Gourlet, P., Dervieux, F., Eveillard, L., Garcin, S. (2015) 'Designing learning tools for education', workshop DRS/LearnxDesign 2015, Chicago, USA.</li>
<li><a href="http://www.pearson.fr/livre/?GCOI=27440100443180" target="_blank">Projets créatifs avec Arduino</a> (2014)</li>
<li><a href="img/hab/hab-intramuros-172.jpg" target="_blank">Intramuros #172</a> (May 2014)</li>
<li><a href="http://www.mouvement.net/analyses/enquetes/faciliter-lacces-au-web" target="_blank">Mouvement #72</a> (January-February 2014)</li>
<li>étapes: #216 (October 2013)</li>
<li>étapes: #209 (October 2012)</li>
							</ul>
				    </div>
		    	</div>
		    	<div class="column collapsable is-collapsed">
				    <div class="content expositions">
							<div class="header">
								<h3>Exhibitions</h3>
							</div>
							<ul class="description">
<li>Presentation of the <a href="http://www.letricodeur.com" data-link='tricodeur' target="_blank">Tricodeur</a> at the Semaine Digitale, in Bordeaux (2014)</li>
<li><a href="http://chercherletexte.org/fr/" target="_blank">Chercher le Texte</a>, Bibliothèque Nationale de France, in Paris (2013)</li>
<li>Salon du livre, Paris (2013)</li>
<li>Amnesia/Memoria, Marke.06, Weimar, Allemagne (2010)</li>
<li>Nooka Show, Red Bull Space in New York, États-Unis (2010)</li>
							</ul>
				    </div>
		    	</div>
			</div>
		</div>
<?php
endif;
*/
?>


	</section>

</main>

<?php snippet('projetlistetvignettes'); ?>

<?php snippet('footer') ?>

