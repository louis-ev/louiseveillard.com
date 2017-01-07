
  <footer>
    <p style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
    </p>
  </footer>

  <!-- scripts -->
  <?= js('assets/plugins/kirby-tracking/js/kirby-tracking.js') ?>
  <?php if ( c::get('environment') == 'local' ) : ?>

  <?= js('assets/js/plugins.js') ?>
  <?= js('assets/js/main.js') ?>

  <?php else: ?>

  <?= js('assets/production/all.min.js') ?>

  <?php endif ?>
	<script>
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-37336212-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
</body>
</html>
