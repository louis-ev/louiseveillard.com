<nav class="module--navbar">
  <div class="module--navbar--wrapper">
  	<div class="module--logo">
  	    <a class="logo" href="<?php echo url() ?>">
    	     Louis Eveillard
  <!--
  				<svg version="1.1"
  	 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
  	 x="0px" y="0px" width="253.7px" height="91px" viewBox="0 0 253.7 91" style="enable-background:new 0 0 253.7 91;"
  	 xml:space="preserve">
  				<style type="text/css">
  					.st0{fill:#FFFFFF;}
  				</style>
  				<defs>
  				</defs>
  				<path class="st0" d="M13.8,77.3h39.8V91H0V0h13.8V77.3z"/>
  				<path class="st0" d="M209.1,13.7v23.7H229v13.8h-19.9v26.1h44.5V91h-58.5V0h58.5v13.7H209.1z"/>
  				<g>
  					<path class="st0" d="M173.4,39.3v9.9H35.7v-9.9H173.4 M175.4,37.3H33.7v13.9h141.7V37.3L175.4,37.3z"/>
  				</g>
  				</svg>
  -->
  	    </a>


  	</div>
  	<ul class="module--navbar--list">
  	  <?php foreach($pages->visible() as $p): ?>
  	  <li class="module--navbar--list--item">
  	    <a <?php e($p->isOpen(), ' class="active"') ?> href="<?php echo $p->url() ?>"><?php echo $p->title()->html() ?></a>
  	  </li>
  	  <?php endforeach ?>
  	</ul>
  </div>
</nav>