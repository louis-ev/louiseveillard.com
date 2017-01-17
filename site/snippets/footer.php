
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
</body>
</html>
