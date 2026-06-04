<?php

global $wp_roles;

$bulletin_background_color_default = BULLETINWP::instance()->sql->get_option( 'bulletin_background_color_default' );
$bulletin_font_color_default       = BULLETINWP::instance()->sql->get_option( 'bulletin_font_color_default' );
$site_has_fixed_header             = BULLETINWP::instance()->sql->get_option( 'site_has_fixed_header' );
$fixed_header_selector             = BULLETINWP::instance()->sql->get_option( 'fixed_header_selector' );

if ( bulletinwp_fs()->is__premium_only() ) {
  $allow_users_to_manage_bulletins   = BULLETINWP::instance()->sql->get_option( 'allow_users_to_manage_bulletins' );
  $all_users                         = get_users( 'orderby=ID' );
  $all_roles                         = $wp_roles->roles;
}

?>

<div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-admin' ) ?>">
  <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-admin-settings wrap' ) ?>">
    <h1 class="wp-heading-inline"><?php _e( 'Settings', BULLETINWP_PLUGIN_SLUG ) ?></h1>
    <hr class="wp-header-end">

    <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-admin-common-layout ' . BULLETINWP_PLUGIN_SLUG . '-admin-settings' ) ?>">
      <form class="settings-form" method="post">
        <div class="common-layout-wrapper settings">
          <div class="content">
            <div class="left-content">
              <div class="box-container p-8 mb-16">
                <!-- Default color settings -->
                <div class="heading mb-3"><?php _e( 'Default color settings', BULLETINWP_PLUGIN_SLUG ) ?></div>
                <label class="mb-3"><?php _e( 'Setting these will apply as the default colors to all bulletins you publish', BULLETINWP_PLUGIN_SLUG ) ?></label>

                <div class="flex mb-8">
                  <div class="flex flex-col mr-4">
                    <label class="mb-1" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-background-color' ) ?>"><?php _e( 'Background color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-background-color' ) ?>"
                           class="color-picker-input"
                           type="text"
                           name="bulletinBackgroundColorDefault"
                           value="<?php echo esc_attr( $bulletin_background_color_default ) ?>"
                           placeholder=""
                           data-default-color=""
                    />
                  </div>

                  <div class="flex flex-col">
                    <label class="mb-1" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-font-color' ) ?>"><?php _e( 'Font color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-font-color' ) ?>"
                           class="color-picker-input"
                           type="text"
                           name="bulletinFontColorDefault"
                           value="<?php echo esc_attr( $bulletin_font_color_default ) ?>"
                           placeholder=""
                           data-default-color=""
                    />
                  </div>
                </div>

                <!-- Header configuration -->
                <div class="heading mb-3"><?php _e( 'Header configuration', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <div class="mb-3">If you want to use the header bulletin under your navbar, or if you have a fixed header, you should define the css class below.<br />For further instruction, please check out <a href="https://www.youtube.com/watch?v=oMV1_aKk-v4" target="_blank">this video for placing a bulletin <b>under your header</b></a> or <a href="https://www.youtube.com/watch?v=yIKVI_3dfJs" target="_blank">this video if you have a <b>fixed header</b></a></div>

                <!-- Header Selector -->
                <div class="font-bold mb-3"><?php _e( 'Header CSS selector', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <div class="form-field mb-4">
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-header-selector' ) ?>"
                    class="w-full form-input"
                    type="text"
                    name="fixedHeaderSelector"
                    value="<?php echo esc_attr( $fixed_header_selector ) ?>"
                    placeholder="header.header"
                  />
                </div>

                <!-- Site has fixed header -->
                <div class="mb-8">
                  <label class="mb-3"><?php _e( 'My site has a fixed header', BULLETINWP_PLUGIN_SLUG ) ?></label>

                  <div class="checkbox-wrapper toggle-switch"
                       data-checked-label="<?php echo esc_attr( __( 'Yes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                       data-unchecked-label="<?php echo esc_attr( __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                  >
                    <input type="checkbox" name="siteHasFixedHeader" <?php checked( $site_has_fixed_header ) ?> />
                    <span class="label"><?php echo esc_html( $site_has_fixed_header ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
                  </div>
                </div>

                <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                  <!-- Access Control -->

                  <div class="heading mb-3"><?php _e( 'Access control', BULLETINWP_PLUGIN_SLUG ) ?></div>

                  <div class="form-field mb-6 is-required">
                    <div><?php _e( 'Only allow the following users or roles to manage bulletins. By default only administrators have access.', BULLETINWP_PLUGIN_SLUG ) ?></div>
                    <select id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-user-role' ) ?>"
                            class="form-input select2 select-resolve-width"
                            name="<?php echo esc_attr( ( current_user_can( 'manage_options' ) || current_user_can( 'editor' ) ) ? 'allowUsersToManageBulletins[]' : '' ) ?>"
                            <?php echo esc_attr( ( current_user_can( 'manage_options' ) || current_user_can( 'editor' ) ) ? '' : 'disabled' ) ?>
                            multiple="multiple"
                            style="width: 75%"
                    >
                      <option value="" disabled>
                        <?php _e( 'Select roles', BULLETINWP_PLUGIN_SLUG ) ?>
                      </option>
                      <?php foreach ( $all_roles as $key => $role ) : ?>
                        <?php
                        $selected = false;
                        $locked   = '';

                        if ( $key === 'administrator' ) {
                          $locked = 'locked="locked"';
                          $selected = true;
                        }

                        foreach ( $allow_users_to_manage_bulletins as $allow_user_to_manage_bulletins ) {
                          if ( $key === $allow_user_to_manage_bulletins['allow_user'] ) {
                            $selected = true;
                          }
                        }
                        ?>

                        <option value="<?php echo esc_attr( $key ) ?>"
                          <?php echo wp_kses( $locked, [] ) ?>
                          <?php selected( $selected )?>>
                          <?php echo esc_html( $role['name'] ) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php endif; ?>

                <!-- Export -->
                <div class="heading mb-3"><?php _e( 'Export', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <div class="export-bulletins-button-wrapper mb-6">
                  <button type="button"
                          class="btn btn-smaller"
                          data-default-label="<?php esc_attr_e( 'Export', BULLETINWP_PLUGIN_SLUG ) ?>"
                          data-loading-label="<?php esc_attr_e( 'Exporting...', BULLETINWP_PLUGIN_SLUG ) ?>"
                  >
                    <?php _e( 'Export', BULLETINWP_PLUGIN_SLUG ) ?>
                  </button>
                  <div class="export-results-message mt-4" style="display: none;"></div>
                </div>

                <!-- Import -->
                <div class="heading mb-3"><?php _e( 'Import', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <div class="import-bulletins-button-wrapper mb-6">
                  <div class="flex flex-col">
                    <input type="file" accept=".csv" />
                    <button id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-import-bulletins-button' ) ?>"
                            type="button"
                            class="btn btn-smaller"
                            data-default-label="<?php esc_attr_e( 'Import', BULLETINWP_PLUGIN_SLUG ) ?>"
                            data-loading-label="<?php esc_attr_e( 'Importing...', BULLETINWP_PLUGIN_SLUG ) ?>">
                      <?php _e( 'Import', BULLETINWP_PLUGIN_SLUG ) ?>
                    </button>
                    <div class="import-results-message mt-4" style="display: none;"></div>
                  </div>
                </div>
              </div>

              <?php if ( ! bulletinwp_fs()->is__premium_only() ) : ?>
                <?php $is_settings_page = true; ?>
                <?php include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/common/upgrade-panel.php' ); ?>
              <?php endif; ?>
            </div>
            <div class="right-content">
              <div class="box-container py-8 px-4">
                <button class="btn-fill text-lg"
                        type="submit"
                        data-default-label="<?php esc_attr_e( 'Save', BULLETINWP_PLUGIN_SLUG ) ?>"
                        data-loading-label="<?php esc_attr_e( 'Saving...', BULLETINWP_PLUGIN_SLUG ) ?>"
                >
                  <?php _e( 'Save', BULLETINWP_PLUGIN_SLUG ) ?>
                </button>
                <div class="form-message mt-8" style="display: none;"></div>
              </div>

              <!-- Right panel content -->
              <?php include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/common/right-panel.php' ); ?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
