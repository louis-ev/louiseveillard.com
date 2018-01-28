<section class="module--projetList">
  <ol class="module--projetList--titles">
    <?php
      $ordreDesCategories = array(
        "tous"                   => 0,
        "sites"                  => 1,
        "objets-interactifs"    => 2,
        "vr"                     => 3,
        "outils-pedagogiques" => 4,
        "autres"                => 5,
      );
    ?>

  	<h3 class="isotope--item" data-index="0" data-num="<?= $ordreDesCategories["tous"]; ?>">Derniers projets</h3>
  	<h3 class="isotope--item is--hidden" data-type="sites" data-index="0" data-num="<?= $ordreDesCategories["sites"]; ?>">Sites web</h3>
  	<h3 class="isotope--item is--hidden" data-type="objets-interactifs" data-index="0" data-num="<?= $ordreDesCategories["objets-interactifs"]; ?>">Objets interactifs</h3>
  	<h3 class="isotope--item is--hidden" data-type="vr" data-index="0" data-num="<?= $ordreDesCategories["vr"]; ?>">Projets en réalité virtuelle</h3>
  	<h3 class="isotope--item is--hidden" data-type="outils-pedagogiques" data-index="0" data-num="<?= $ordreDesCategories["outils-pedagogiques"]; ?>">Outils pédagogiques</h3>
  	<h3 class="isotope--item is--hidden" data-type="autres" data-index="0" data-num="<?= $ordreDesCategories["autres"]; ?>">Autres projets</h3>
    <?php
      if($user = site()->user() and $user->hasPanelAccess()):
        $projets = $pages->findByURI('projets')->children()->visible();
      else:
        $projets = $pages->findByURI('projets')->children()->visible();
      endif;

      foreach($projets as $p):
        $projectSlug = str::slug( $p->type());
        $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT);
        ?>
        <li class="module--projetList--titles--projetName isotope--item <?php e($p->isOpen(), 'is--active') ?> <?php e(is_visited($p->url()), 'is--visited', '') ?>" data-index="<?= $index ?>" data-type="<?= $projectSlug; ?>" data-num="<?= $ordreDesCategories[$projectSlug]; ?>">
          <a class="module--projetList--titles--projetName--links" href="<?= $p->url() ?>">
            	<span class="module--projetList--titles--projetName--links--index"><?= $index ?></span>
            <span class="module--projetList--titles--projetName--links--name"><?= $p->title()->html() ?></span>
            <span class="module--projetList--titles--projetName--links--corners"></span>
          </a>
        </li>
    <?php
      endforeach;
    ?>
  </ol>

  <div class="module--projetList--visuelWrapper lazyload" data-lazyload-children=".module--projetList--visuelWrapper--visuel">

    <?php foreach($projets as $p): ?>
      <?php $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT); ?>
      <?php $isProjetPage = $page->template() == 'projet'; ?>

      <?php
      	if( $p->imageCover()->isNotEmpty()):
      	  $image = $p->imageCover()->toFile();
      		$srcsetstring = kirbytag(array(
      		  'image'  				=> $image->filename(),
      		  'thumbwidth' 		=> 1800,
      		  'originalPage'  => $p,
      		  'return_srcset' => true
      		));

        ?>
        <div class="module--projetList--visuelWrapper--visuel" data-index="<?= $index; ?>" data-sizes="auto" data-optimumx="1" data-bgset="<?= $srcsetstring; ?>"></div>
      <?php
        endif;
      ?>
    <?php endforeach ?>
    <div class="module--projetList--visuelWrapper--bgmask"></div>
  </div>

</section>