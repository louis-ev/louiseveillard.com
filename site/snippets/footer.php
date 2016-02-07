
  <footer>
    <p style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
    </p>
  </footer>

	<div class="module--gradient_overlay <?php e( $page->intendedTemplate() === "home", '', 'is--away'); ?>">
    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
      <defs>
      <radialGradient id="GradientPad" cx="-.2" cy=".5" r="1" fx="0" fy=".5" spreadMethod="pad">
      <stop offset="0%" stop-color="rgba(21, 21, 21, 1)"></stop>
      <stop offset="100%" stop-color="rgba(21, 21, 21, .4)"></stop>
      </radialGradient>
			<linearGradient y2="0" x2="1" y1="0" x1="0" id="svg_2">
					   <stop stop-color="rgba(21, 21, 21, 1)" stop-opacity=".6" offset="0"></stop>
					   <stop stop-color="rgba(21, 21, 21, 1)" stop-opacity=".6" offset="1"></stop>
					  </linearGradient>

      </defs>
      <g>
      <title>Layer 1</title>

      <rect x="0" y="0" rx="0" ry="0" width="100%" height="100%" fill="url(#svg_2)"></rect>

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
