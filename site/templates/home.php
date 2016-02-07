<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">

	<div class="module--intro">
		<header class="module--into--textblock">
	  	<?php echo $page->text()->kirbytext() ?>
	  </div>
	</div>

	<ol class="module--projetList">
		<h3 class="isotope--item" data-num="0">Derniers projets</h3>
		<h3 class="isotope--item" data-type="sites" style="display: none;">Sites</h3>
		<h3 class="isotope--item" data-type="vr" style="display: none;">Projets en réalité virtuelle</h3>
		<h3 class="isotope--item" data-type="installations" style="display: none;">Installations interactives</h3>
		<h3 class="isotope--item" data-type="outils-pedagogiques" style="display: none;">Outils pédagogiques</h3>

    <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
    <li class="module--projetList--projetName isotope--item <?php e($p->isOpen(), 'is--active') ?>" data-index="<?= $p->num(); ?>" data-type="<?= str::slug( $p->type()); ?>" >
      <a class="module--projetList--projetName--links" href="<?= $p->url() ?>"><?= $p->title()->html() ?></a>
    </li>
    <?php endforeach ?>
  </ol>

  <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
		<div class="module--projet" data-index="<?= $p->num(); ?>" data-num="<?= $p->num(); ?>">
			<?php snippet( "projet--header", array( 'projet' => $p)); ?>
		</div>
	<?php endforeach ?>
</main>
<?php snippet('footer') ?>