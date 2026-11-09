<nav class="module--navbar">
  <div class="module--navbar--wrapper">
    <div class="module--logo">
      <a class="logo" href="<?= $site->url() ?>">Louis Eveillard</a>
    </div>
    <ul class="module--navbar--list">
      <?php foreach ($site->children()->listed() as $p): ?>
      <li class="module--navbar--list--item">
        <a data-goto="<?= Str::slug($p->title()) ?>"<?= r($p->isOpen(), ' class="active"') ?> href="<?= $p->url() ?>"><?= $p->title()->html() ?></a>
      </li>
      <?php endforeach ?>
    </ul>
  </div>
</nav>

<?php snippet('archive') ?>
