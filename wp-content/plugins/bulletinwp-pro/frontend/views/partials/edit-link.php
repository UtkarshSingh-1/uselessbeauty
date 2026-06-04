<?php
$bulletin_edit_link = add_query_arg(
  [
    'page'     => "{$plugin_slug}-options-edit",
    'bulletin' => $bulletin['id'],
  ],
  admin_url( 'admin.php' )
);

if ( bulletinwp_fs()->is__premium_only() ) {
  if ( is_multisite() && ! is_main_site() && ( strpos( $bulletin['id'], 'global-' ) !== false ) ) {
    $bulletin_edit_link = add_query_arg(
      [
        'page'     => "{$plugin_slug}-options-edit",
        'bulletin' => str_replace( 'global-', '', $bulletin['id'] ),
      ],
      str_replace( '/network', '', network_admin_url( 'admin.php' ) )
    );
  }
}
?>
<a href="<?php echo esc_url( $bulletin_edit_link ) ?>"
   class="<?php echo esc_attr( "{$plugin_slug}-bulletin-admin-edit-link" ) ?>"
>
  <?php _e( 'edit', $plugin_slug ) ?>
</a>
