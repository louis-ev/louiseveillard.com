<ol class="module--projetList">
	<h3 class="isotope--item" data-num="0">Derniers projets</h3>
	<h3 class="isotope--item" data-type="sites" style="display: none;">Sites</h3>
	<h3 class="isotope--item" data-type="vr" style="display: none;">Projets en réalité virtuelle</h3>
	<h3 class="isotope--item" data-type="installations" style="display: none;">Installations interactives</h3>
	<h3 class="isotope--item" data-type="outils-pedagogiques" style="display: none;">Outils pédagogiques</h3>
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