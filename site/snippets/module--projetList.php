<ol class="module--projetList">
	<h3 class="isotope--item" data-num="0">Derniers projets</h3>
	<h3 class="isotope--item is--hidden" data-type="sites" data-num="100">Sites</h3>
	<h3 class="isotope--item is--hidden" data-type="vr" data-num="100">Projets en réalité virtuelle</h3>
	<h3 class="isotope--item is--hidden" data-type="objets-interactifs" data-num="100">Installations interactives</h3>
	<h3 class="isotope--item is--hidden" data-type="outils-pedagogiques" data-num="100">Outils pédagogiques</h3>
  <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
    <?php $index = str_pad( $p->num(), 2, '0', STR_PAD_LEFT); ?>
    <li class="module--projetList--projetName isotope--item <?php e($p->isOpen(), 'is--active') ?>" data-index="<?= $index ?>" data-type="<?= str::slug( $p->type()); ?>" >
      <a class="module--projetList--projetName--links" href="<?= $p->url() ?>">
      	<span class="module--projetList--projetName--links--index"><?= $index ?></span>
        <?= $p->title()->html() ?>
      </a>
    </li>
  <?php endforeach ?>
</ol>
