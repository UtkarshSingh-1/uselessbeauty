<?php
  isset( $is_settings_page ) or $is_settings_page = false;

?>

<div class="flex flex-col md:flex-row mt-16">
  <?php if ( ! $is_settings_page ) : ?>
    <div style="width: 300px;" class="hidden md:block flex-shrink-0"></div>
  <?php endif; ?>

  <div class="relative flex-grow text-center border-2 border-blue-100 p-4 md:p-12">
    <div class="box-heading">
      <h2><?php _e( 'Upgrade to PRO', BULLETINWP_PLUGIN_SLUG ) ?></h2>
    </div>

    <div class="mb-12"><?php _e( 'Upgrade to get instant access to the following features', BULLETINWP_PLUGIN_SLUG ) ?></div>

    <div class="flex flex-wrap text-left justify-center -mx-4 mb-10">
      <div class="flex flex-col px-4">
        <div class="bullet-item mb-4">
          <h4><?php _e( 'Schedule and Expire Bulletins', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Add rotating messages or marquees', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Add a countdown clock', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Include / exclude bulletins on certain pages', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Show bulletin for logged-in users', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Remove "Powered by Bulletin" element', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>
      </div>

      <div class="flex flex-col px-4">

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Eye-popping icons', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Custom button & Actions', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Custom Google fonts', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Add advanced CSS to bulletins', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>

        <div class="bullet-item mb-4">
          <h4><?php _e( 'Wordpress Network support', BULLETINWP_PLUGIN_SLUG ) ?></h4>
        </div>
      </div>
    </div>

    <div class="text-center mb-8">
      <div class="leading-none"><?php _e( 'from', BULLETINWP_PLUGIN_SLUG ) ?></div>
      <div><span class="font-playfair font-bold leading-none text-6xl md:text-2xxl">$19</span> / <?php _e( 'year', BULLETINWP_PLUGIN_SLUG ) ?></div>
    </div>

    <div class="mb-4">
      <a href="<?php echo esc_url( bulletinwp_fs()->pricing_url() ) ?>"
        class="btn-fill"
      >
        <span><?php _e( 'See all pricing options', BULLETINWP_PLUGIN_SLUG ) ?></span>
        <img src="<?php echo esc_url( $images_dir . '/angle-white.svg' ) ?>" alt="">
      </a>
    </div>

    <a href="https://bulletin.rocks?utm_source=WordPress&utm_campaign=freeplugin&utm_medium=upgrade-panel" target="_blank"><?php _e( 'Learn more', BULLETINWP_PLUGIN_SLUG ) ?></a>
  </div>
</div>
