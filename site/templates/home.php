<?php snippet('header') ?>
<?php snippet('menu') ?>

<main class="main" role="main">

	<div class="module--intro">
	  <?php echo $page->text()->kirbytext() ?>
	</div>

	<ol class="module--projetList">
		<h3>Derniers projets réalisés</h3>
<!--
				alternatives :
					- sites sur lesquels j'ai travaillé récemment
					- installation que j'ai créé
					- projets de réalité virtuelle que j'ai développé
					- outils pédagogiques créés
-->
    <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
	    <li class="module--projetList--projectName" >
	      <a <?php e($p->isOpen(), ' class="active"') ?> data-num="<?= $p->num(); ?>" href="<?php echo $p->url() ?>"><?php echo $p->title()->html() ?></a>
	    </li>
    <?php endforeach ?>
  </ol>

  <?php foreach($pages->findByURI('projets')->children()->visible() as $p): ?>
		<div class="module--projet" data-num="<?= $p->num(); ?>" style="display: none;">
			<?php snippet( "projet--header", array( 'projet' => $p)); ?>
		</div>
	<?php endforeach ?>
</main>
<?php snippet('footer') ?>