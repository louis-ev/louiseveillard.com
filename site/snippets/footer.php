
  <footer>
    <p style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
    </p>
  </footer>

	<div class="module--gradient_overlay <?php e( $page->intendedTemplate() === "home", '', 'is--away'); ?>">
		<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
		 <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
		 <defs>
			<linearGradient y2="0" x2="1" y1=".2" x1="0" id="svg_2">
					   <stop stop-color="rgba(21, 21, 21, 1)" stop-opacity="1" offset="0"></stop>
					   <stop stop-color="rgba(21, 21, 21, 0)" stop-opacity="0" offset=".4"></stop>
					  </linearGradient>
		 </defs>
		 <g>
		  <title>Layer 1</title>
		  <rect id="svg_1" height="100%" width="100%" y="0" x="0"  fill="url(#svg_2)"/>
		 </g>
		</svg>
	</div>

  <!-- scripts -->
  <?php if ( c::get('environment') == 'local' ) : ?>

  <?= js('assets/js/plugins.js') ?>
  <?= js('assets/js/main.js') ?>

  <?php else: ?>

  <?= js('assets/production/all.min.js') ?>

  <?php endif ?>

</body>
</html>
