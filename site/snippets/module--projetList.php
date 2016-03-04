<ol class="module--projetList">
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
    foreach($pages->findByURI('projets')->children()->visible() as $p):
      $projectSlug = str::slug( $p->type());
      $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT);
      ?>
      <li class="module--projetList--projetName isotope--item <?php e($p->isOpen(), 'is--active') ?>" data-index="<?= $index ?>" data-type="<?= $projectSlug; ?>" data-num="<?= $ordreDesCategories[$projectSlug]; ?>">
        <a class="module--projetList--projetName--links" href="<?= $p->url() ?>">
        	<span class="module--projetList--projetName--links--index"><?= $index ?></span>
          <?= $p->title()->html() ?>
        </a>
      </li>
  <?php
    endforeach;
  ?>
</ol>
