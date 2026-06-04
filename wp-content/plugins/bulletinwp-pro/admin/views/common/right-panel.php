<?php
$content_arr = [
  [
    'icon'    => 'knowledge',
    'link'    => 'https://www.bulletin.rocks/docs/installation/',
    'content' => __( 'View plugin docs & FAQs', BULLETINWP_PLUGIN_SLUG ),
  ],
  [
    'icon'    => 'growth-bars',
    'link'    => 'mailto:info@rocksoliddigital.com?subject=I%20have%20a%20recommendation%20to%20improve%20Bulletin',
    'content' => __( 'Suggest a feature for our future development', BULLETINWP_PLUGIN_SLUG ),
  ],
  [
    'icon'    => 'influencer-star',
    'link'    => 'https://wordpress.org/support/plugin/bulletin-announcements/reviews/?filter=5#postform',
    'content' => __( 'Like this plugin? Please give us a 5 star review!', BULLETINWP_PLUGIN_SLUG ),
  ],
];

// Images url
$images_url = plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/images/';
?>

<div class="right-content-panel-wrapper">
  <p class="text-base font-bold mb-5"><?php _e( 'Explore more from Bulletin', BULLETINWP_PLUGIN_SLUG ) ?></p>

  <?php foreach ( $content_arr as $key => $content ) : ?>
    <?php
    $icon_file_url =  $images_url . $content['icon'] . '.svg';
    ?>

    <a href="<?php echo esc_url( $content['link'] ) ?>" target="_blank" class="btn-text">
      <div class="flex flex-row mb-8 items-center">
        <div class="mr-4">
          <img src="<?php echo esc_url( $icon_file_url ) ?>" alt="">
        </div>

        <div>
          <p class="text-base font-medium"><?php echo wp_kses_post( $content['content'] ); ?></p>
        </div>
      </div>
    </a>

  <?php endforeach; ?>
</div>
