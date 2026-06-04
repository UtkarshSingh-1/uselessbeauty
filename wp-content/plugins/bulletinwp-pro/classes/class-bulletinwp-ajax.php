<?php

defined( 'ABSPATH' ) or exit;

class BULLETINWP_Ajax {
  private static $menu_page_base_slug = BULLETINWP_PLUGIN_SLUG . '-options';

  public function __construct() {
    $actions = [
      'bulletinwp_update_bulletin_status',
      'bulletinwp_update_bulletin',
      'bulletinwp_update_settings',
      'bulletinwp_update_status',
      'bulletinwp_export_bulletins',
      'bulletinwp_import_bulletins',
    ];

    foreach ( $actions as $action ) {
      /**
       * For admin ajax
       */
      add_action( "wp_ajax_$action", array( $this, $action ) );

      /**
       * For front end ajax; Only enable below if any front end ajax used
       */
      // add_action( "wp_ajax_nopriv_$action", array( $this, $action ) );
    }
  }

  /**
   * Update bulletin status
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function bulletinwp_update_bulletin_status() {
    $bulletin_id   = sanitize_text_field( $_POST['bulletinID'] );
    $status_action = sanitize_text_field( $_POST['statusAction'] );

    if ( ! empty( $status_action ) ) {
      switch ( $status_action ) {
        case 'activate':
          BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', true );
          break;
        case 'deactivate':
          BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', false );
          break;
        default:
          break;
      }
    }

    wp_send_json_success( [
      'message' => __( 'Settings saved successfully.', BULLETINWP_PLUGIN_SLUG ),
    ] );
  }

  /**
   * Update bulletin
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function bulletinwp_update_bulletin() {
    $plugin_slug = BULLETINWP_PLUGIN_SLUG;
    $bulletin    = sanitize_text_field( $_POST['bulletin'] );
    parse_str( $_POST['formData'], $form_data );

    $args             = [];
    $is_activated     = false;
    $edit_page_params = [
      'page'     => self::$menu_page_base_slug . '-edit',
      'bulletin' => '',
    ];
    $bulletin_link = '';
    $updated_data  = [];

    if ( ! empty( $bulletin ) ) {
      $args['id'] = $bulletin;
    }

    if ( ! empty( $form_data ) && is_array( $form_data ) ) {
      $bulletin_column_names_map = [
        // '[name on the form]' => '[name on the database]',
        'isActivated'             => 'is_activated',
        'bulletinTitle'           => 'bulletin_title',
        'content'                 => 'content',
        'mobileContent'           => 'mobile_content',
        'backgroundColor'         => 'background_color',
        'fontColor'               => 'font_color',
        'placement'               => 'placement',
        'headerBannerStyle'       => 'header_banner_style',
        'headerBannerScroll'      => 'header_banner_scroll',
        'contentMaxWidth'         => 'content_max_width',
        'textAlignment'           => 'text_alignment',
        'fontSize'                => 'font_size',
        'fontSizeMobile'          => 'font_size_mobile',
        'textVerticalPadding'     => 'text_vertical_padding',
      ];

      $content_column_names = [
        'content',
        'mobileContent',
      ];

      if ( bulletinwp_fs()->is__premium_only() ) {
        $bulletin_column_names_map = array_merge( $bulletin_column_names_map, [
          'placementCornerPosition'         => 'placement_corner_options',
          'addIcon'                         => 'add_icon',
          'iconNameFromSet'                 => 'icon_name_from_set',
          'iconAttachmentID'                => 'icon_attachment_id',
          'addImage'                        => 'add_image',
          'imageAttachmentID'               => 'image_attachment_id',
          'imageAlignment'                  => 'image_alignment',
          'imageMaxWidth'                   => 'image_max_width',
          'fonts'                           => 'fonts',
          'googleFonts'                     => 'google_fonts',
          'isMultipleMessages'              => 'is_multiple_messages',
          'messages'                        => 'messages',
          'rotationStyle'                   => 'rotation_style',
          'cycleSpeed'                      => 'cycle_speed',
          'marqueeSpeed'                    => 'marquee_speed',
          'addButton'                       => 'add_button',
          'buttonLabel'                     => 'button_label',
          'buttonMobileLabel'               => 'button_mobile_label',
          'buttonBackgroundColor'           => 'button_background_color',
          'buttonFontColor'                 => 'button_font_color',
          'buttonHoverBackgroundColor'      => 'button_hover_background_color',
          'buttonHoverFontColor'            => 'button_hover_font_color',
          'buttonAction'                    => 'button_action',
          'buttonCookieExpiry'              => 'button_cookie_expiry',
          'buttonLink'                      => 'button_link',
          'buttonTarget'                    => 'button_target',
          'buttonAlign'                     => 'button_align',
          'buttonJSEvent'                   => 'button_js_event',
          'buttonEasyPopup'                 => 'button_easy_popup',
          'addCountdown'                    => 'add_countdown',
          'countdown'                       => 'countdown',
          'showCountdown'                   => 'show_countdown',
          'countdownBackgroundColor'        => 'countdown_background_color',
          'countdownFontColor'              => 'countdown_font_color',
          'countdownDaysLabel'              => 'countdown_days_label',
          'countdownHoursLabel'             => 'countdown_hours_label',
          'countdownMinsLabel'              => 'countdown_mins_label',
          'countdownSecsLabel'              => 'countdown_secs_label',
          'placementByContent'              => 'placement_by_content',
          'placementSelectedContentInclude' => 'placement_selected_content_include',
          'placementSelectedContentExclude' => 'placement_selected_content_exclude',
          'placementByUser'                 => 'placement_by_user',
          'placementUserCookieValue'        => 'placement_user_cookie_value',
          'isDismissable'                   => 'is_dismissable',
          'cookieExpiry'                    => 'cookie_expiry',
          'additionalCss'                   => 'additional_css',
          'addSchedule'                     => 'add_schedule',
          'startSchedule'                   => 'start_schedule',
        ] );

        if ( is_multisite() && is_main_site() ) {
          $bulletin_column_names_map = array_merge( $bulletin_column_names_map, [
            'applyAllSubsites' => 'apply_all_subsites',
          ] );
        }

        $content_column_names = array_merge( $content_column_names, [
          'messages',
        ] );
      }

      // Validate the placement value
      if ( isset( $form_data['placement'] ) ) {
        $allowed_placements = [ 'top', 'float-bottom', 'sticky-footer' ];

        if ( bulletinwp_fs()->is__premium_only() ) {
          $allowed_placements = array_merge( $allowed_placements, [ 'corner' ] );
        }

        if ( ! in_array( $form_data['placement'], $allowed_placements ) ) {
          $form_data['placement'] = '';
        }
      }

      if ( bulletinwp_fs()->is__premium_only() ) {
        if ( isset( $form_data['messageContent'] ) && isset( $form_data['messageMobileContent'] ) ) {
          $messages = [];

          if ( ! empty( $form_data['messageContent'] ) && is_array( $form_data['messageContent'] ) && ! empty( $form_data['messageMobileContent'] ) && is_array( $form_data['messageMobileContent'] ) ) {
            // Remove the cloned item
            array_pop( $form_data['messageContent'] );
            array_pop( $form_data['messageMobileContent'] );

            foreach ( $form_data['messageContent'] as $key => $message ) {
              $messages[ $key ] = [
                'content'        => $message,
                'mobile_content' => '',
              ];

              if ( array_key_exists( $key, $form_data['messageMobileContent'] ) && ! empty( $form_data['messageMobileContent'][ $key ] ) ) {
                $messages[ $key ]['mobile_content'] = $form_data['messageMobileContent'][ $key ];
              }
            }
          }

          $form_data['messages'] = array_values( $messages );
        }

        if ( isset( $form_data['placementSelectedContentInclude'] ) ) {
          $placement_selected_content_include = [];

          $includes = explode( "\n", str_replace( "\r", "", $form_data['placementSelectedContentInclude'] ) );

          if ( ! empty( $includes ) ) {
            foreach ( $includes as $include ) {
              if ( ! empty( $include ) ) {
                $include_arr  = parse_url( $include );
                $path         = $include_arr['path'];
                $include_text = '';

                if ( preg_match( "/^\//", $path ) ) {
                  $include_text = $path;
                } else {
                  $path_arr = explode( "/", $path );

                  if ( count( $path_arr ) > 1 ) {
                    array_shift( $path_arr );

                    $include_text = "/" . implode( "/", $path_arr );
                  }
                }

                if ( ! empty( $include_text ) ) {
                  $query = isset( $include_arr['query'] ) ? $include_arr['query'] : '';
                  if ( ! empty( $query ) ) {
                    $include_text .= "?{$query}";
                  }
                  $placement_selected_content_include[] = $include_text;
                }
              }
            }
          }

          $form_data['placementSelectedContentInclude']    = array_values( $placement_selected_content_include );
          $updated_data['placementSelectedContentInclude'] = implode( "\n", $placement_selected_content_include );
        }

        if ( isset( $form_data['placementSelectedContentExclude'] ) ) {
          $placement_selected_content_exclude = [];

          $excludes = explode( "\n", str_replace( "\r", "", $form_data['placementSelectedContentExclude'] ) );

          if ( ! empty( $excludes ) ) {
            foreach ( $excludes as $exclude ) {
              if ( ! empty( $exclude ) ) {
                $exclude_arr  = parse_url( $exclude );
                $path         = $exclude_arr['path'];
                $exclude_text = '';

                if ( preg_match( "/^\//", $path ) ) {
                  $exclude_text = $path;
                } else {
                  $path_arr = explode( "/", $path );

                  if ( count( $path_arr ) > 1 ) {
                    array_shift( $path_arr );

                    $exclude_text = "/" . implode( "/", $path_arr );
                  }
                }

                if ( ! empty( $exclude_text ) ) {
                  $query = isset( $exclude_arr['query'] ) ? $exclude_arr['query'] : '';
                  if ( ! empty( $query ) ) {
                    $exclude_text .= "?{$query}";
                  }
                  $placement_selected_content_exclude[] = $exclude_text;
                }
              }
            }
          }

          $form_data['placementSelectedContentExclude']    = array_values( $placement_selected_content_exclude );
          $updated_data['placementSelectedContentExclude'] = implode( "\n", $placement_selected_content_exclude );
        }

        // Set is_active to false if schedule is enabled
        if ( isset( $form_data['addSchedule'] ) && $form_data['addSchedule'] === 'on' ) {
          $form_data['isActivated'] = 'off';
        }
      }

      foreach ( $form_data as $key => $field ) {
        if ( array_key_exists( $key, $bulletin_column_names_map ) ) {
          if ( in_array( $key, $content_column_names ) ) {
            $allowed_html = [
              'strong' => [],
              'em'     => [],
              'b'      => [],
              'i'      => [],
              'span'   => [],
              'sup'    => [],
              'sub'    => [],
              'mark'   => [],
              'a'      => [
                'href'   => [],
                'target' => [],
              ],
              'img' => [
                'class' => [],
                'style' => [],
                'src'   => [],
              ],
            ];

            if ( is_array( $field ) ) {
              $updated_field = [];

              foreach ( $field as $key_1 => $field_item ) {
                if ( is_array( $field_item ) ) {
                  foreach ( $field_item as $key_2 => $item ) {
                    $updated_value = stripslashes( $item );
                    $updated_value = wp_kses( $updated_value, $allowed_html );

                    $updated_field[ $key_1 ][ $key_2 ] = $updated_value;
                  }
                }
              }

              $field = serialize( $updated_field );
            } else {
              $field = stripslashes( $field );
              $field = wp_kses( $field, $allowed_html );
            }
          } elseif ( is_array( $field ) ) {
            $field = serialize( $field );
          } elseif ( in_array( strtolower( $field ), [ 'on', 'off' ], true ) ) {
            $field = ( 'on' === strtolower( sanitize_text_field( $field ) ) );
          } else {
            $field = sanitize_text_field( $field );
          }

          $args['data'][ $bulletin_column_names_map[ $key ] ] = $field;
        }
      }

      if ( ! empty( $args['data'] ) ) {
        $is_activated                 = isset( $args['data']['is_activated'] ) ? $args['data']['is_activated'] : false;
        $bulletin_id                  = BULLETINWP::instance()->sql->update_bulletin( $args );
        $bulletin_title               = ( isset( $args['data']['bulletin_title'] ) && ! empty( $args['data']['bulletin_title'] ) ) ? $args['data']['bulletin_title'] : '';
        $edit_page_params['bulletin'] = $bulletin_id;
        $bulletin_link                = BULLETINWP::instance()->helpers->get_bulletin_link( $bulletin_id );

        // Update changes on customizer fields
        if ( $bulletin_id === get_theme_mod( "{$plugin_slug}-general-section-bulletin-id" ) ) {
          if ( isset( $args['data']['content'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-content", $args['data']['content'] );
          }

          if ( isset( $args['data']['mobile_content'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-mobile-content", $args['data']['mobile_content'] );
          }

          if ( isset( $args['data']['background_color'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-background-color", $args['data']['background_color'] );
          }

          if ( isset( $args['data']['font_color'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-font-color", $args['data']['font_color'] );
          }

          if ( isset( $args['data']['placement'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-placement", $args['data']['placement'] );
          }

          if ( bulletinwp_fs()->is__premium_only() ) {
            if ( isset( $args['data']['placement_corner_options'] ) ) {
              set_theme_mod( "{$plugin_slug}-general-section-placement-corner-options", $args['data']['placement_corner_options'] );
            }
          }

          if ( isset( $args['data']['content_max_width'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-content-max-width", $args['data']['content_max_width'] );
          }

          if ( isset( $args['data']['text_alignment'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-text-alignment", $args['data']['text_alignment'] );
          }

          if ( isset( $args['data']['font_size'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-font-size", $args['data']['font_size'] );
          }

          if ( isset( $args['data']['font_size_mobile'] ) ) {
            set_theme_mod( "{$plugin_slug}-general-section-font-size-mobile", $args['data']['font_size_mobile'] );
          }
        }

        if ( bulletinwp_fs()->is__premium_only() ) {
          // Clear previous bulletin cron event if set
          wp_clear_scheduled_hook( "{$plugin_slug}_set_start_date_bulletin_schedule", [ $bulletin_id ] );
          wp_clear_scheduled_hook( "{$plugin_slug}_set_end_date_bulletin_schedule", [ $bulletin_id ] );

          if ( isset( $args['data']['add_schedule'] )
               && $args['data']['add_schedule']
               && isset( $args['data']['start_schedule'] )
               && $args['data']['start_schedule']
          ) {
            $start_schedule = $args['data']['start_schedule'];

            if ( function_exists( 'wp_date' ) ) {
              try {
                $start_schedule_date_time = new \DateTime( $start_schedule );

                if ( ! empty( $timezone_string = BULLETINWP::instance()->helpers->get_timezone_string() ) ) {
                  $start_schedule_date_time->setTimezone( new \DateTimeZone( $timezone_string ) );
                }

                // Get the offset of the timezone to set the date time correctly on the cron event
                $timezone_offset = $start_schedule_date_time->getOffset() / 3600;

                if ( $timezone_offset > 0 ) {
                  $sub_hours = $timezone_offset * 2;
                  $start_schedule_date_time->sub( new \DateInterval( "PT{$sub_hours}H" ) );
                } elseif ( $timezone_offset < 0 ) {
                  $add_hours = $timezone_offset * - 2;
                  $start_schedule_date_time->add( new \DateInterval( "PT{$add_hours}H" ) );
                }

                $start_schedule_date_time = $start_schedule_date_time->format( 'm/d/Y H:i:s' );
              } catch ( Exception $exception ) {
                $start_schedule_date_time = '';
              }
            } else {
              $start_schedule_date_time = gmdate( 'm/d/Y H:i:s', strtotime( $start_schedule ) );
            }

            if ( ! empty( $start_schedule_date_time ) ) {
              // Set bulletin cron event
              wp_schedule_single_event( strtotime( $start_schedule_date_time ), "{$plugin_slug}_set_start_date_bulletin_schedule", [ $bulletin_id ] );
            }
          }

          if ( isset( $args['data']['add_countdown'] )
               && $args['data']['add_countdown']
               && isset( $args['data']['countdown'] )
               && $args['data']['countdown']
          ) {
            $end_schedule = $args['data']['countdown'];

            if ( function_exists( 'wp_date' ) ) {
              try {
                $end_schedule_date_time = new \DateTime( $end_schedule );

                if ( ! empty( $timezone_string = BULLETINWP::instance()->helpers->get_timezone_string() ) ) {
                  $end_schedule_date_time->setTimezone( new \DateTimeZone( $timezone_string ) );
                }

                // Get the offset of the timezone to set the date time correctly on the cron event
                $timezone_offset = $end_schedule_date_time->getOffset() / 3600;

                if ( $timezone_offset > 0 ) {
                  $sub_hours = $timezone_offset * 2;
                  $end_schedule_date_time->sub( new \DateInterval( "PT{$sub_hours}H" ) );
                } elseif ( $timezone_offset < 0 ) {
                  $add_hours = $timezone_offset * - 2;
                  $end_schedule_date_time->add( new \DateInterval( "PT{$add_hours}H" ) );
                }

                $end_schedule_date_time = $end_schedule_date_time->format( 'm/d/Y H:i:s' );
              } catch ( Exception $exception ) {
                $end_schedule_date_time = '';
              }
            } else {
              $end_schedule_date_time = gmdate( 'm/d/Y H:i:s', strtotime( $end_schedule ) );
            }

            if ( ! empty( $end_schedule_date_time ) ) {
              // Set bulletin cron event
              wp_schedule_single_event( strtotime( $end_schedule_date_time ), "{$plugin_slug}_set_end_date_bulletin_schedule", [ $bulletin_id ] );
            }
          }
        }
      }
    }

    wp_send_json_success( [
      'is_activated'     => $is_activated,
      'edit_page_params' => $edit_page_params,
      'bulletin_link'    => $bulletin_link,
      'updated_data'     => $updated_data,
      'message'          => __( 'Bulletin saved.', $plugin_slug ),
    ] );
  }

  /**
   * Update settings
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function bulletinwp_update_settings() {
    parse_str( $_POST['formData'], $form_data );
    $form_data = BULLETINWP::instance()->helpers->array_map_recursive( 'sanitize_text_field', $form_data );
    $all_users = get_users( 'orderby=ID' );
    $all_roles = get_editable_roles();

    if ( ! empty( $form_data ) && is_array( $form_data ) ) {
      // Settings Options
      $settings_options_names_map = [
        // '[name on the form]' => '[name on the database]',
        'bulletinBackgroundColorDefault' => 'bulletin_background_color_default',
        'bulletinFontColorDefault'       => 'bulletin_font_color_default',
        'siteHasFixedHeader'             => 'site_has_fixed_header',
        'fixedHeaderSelector'            => 'fixed_header_selector',
      ];

      if ( bulletinwp_fs()->is__premium_only() ) {
        $settings_options_names_map = array_merge( $settings_options_names_map, [
          'allowUsersToManageBulletins' => 'allow_users_to_manage_bulletins',
        ] );

        if ( isset( $form_data['allowUsersToManageBulletins'] ) ) {
          $allow_roles = [];

          // Remove edit_pages Capability on users
          if ( ! empty( $all_users ) ) {
            foreach ( $all_users as $user ) {
              if ( $user->roles[0] !== 'administrator' && $user->roles[0] !== 'editor' && $user->roles[0] !== 'shop_manager' ) {
                $user_data = get_userdata( $user->ID );
                $user_data->remove_cap( 'edit_pages' );
              }
            }
          }

          // Remove edit_pages Capability on roles
          if ( ! empty( $all_roles ) ) {
            foreach ( $all_roles as $key => $role ) {
              if ( $key !== 'administrator' && $key !== 'editor' && $key !== 'shop_manager' ) {
                $role_object = get_role( $key );
                $role_object->remove_cap( 'edit_pages' );
              }
            }
          }

          if ( ! empty( $form_data['allowUsersToManageBulletins'] ) && is_array( $form_data['allowUsersToManageBulletins'] ) ) {
            foreach ( $form_data['allowUsersToManageBulletins'] as $key => $allow_user ) {
              // Add edit_pages capability on roles
              $modify_role = get_role( $allow_user );
              $modify_role->add_cap( 'edit_pages' );

              $allow_users[ $key ] = [
                'allow_user' => $allow_user,
              ];
            }
          }

          $form_data['allowUsersToManageBulletins'] = array_values( $allow_users );
        } else {
          $allow_roles = [];

          $allow_roles[] = [
            'allow_user' => 'administrator',
          ];

          $form_data['allowUsersToManageBulletins'] = array_values( $allow_users );
        }
      }

      foreach ( $form_data as $key => $field ) {
        if ( array_key_exists( $key, $settings_options_names_map ) ) {
          if ( is_array( $field ) ) {
            $field = serialize( $field );
          } elseif ( in_array( strtolower( $field ), [ 'on', 'off' ], true ) ) {
            $field = ( 'on' === strtolower( sanitize_text_field( $field ) ) );
          } else {
            $field = sanitize_text_field( $field );
          }

          BULLETINWP::instance()->sql->update_option( $settings_options_names_map[ $key ], $field );
        }
      }
    }

    wp_send_json_success( [
      'message' => __( 'Settings saved successfully.', BULLETINWP_PLUGIN_SLUG ),
    ] );
  }

  /**
   * Update bulletin status
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function bulletinwp_update_status() {
    $bulletin_id   = sanitize_text_field( $_POST['bulletinID'] );
    $status_action = sanitize_text_field( $_POST['statusAction'] );

    if ( ! empty( $status_action ) ) {
      switch ( $status_action ) {
        case 'activate':
          BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', true );
          break;
        case 'deactivate':
          BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', false );
          break;
        default:
          break;
      }
    }

    wp_send_json_success( [
      'message' => __( 'Settings saved successfully.', BULLETINWP_PLUGIN_SLUG ),
    ] );
  }

  /**
   * Export all bulletins
   *
   * @since 3.4.0
   *
   * @param void
   * @return void
   */
  public function bulletinwp_export_bulletins() {
    $filename  = 'bulletins_' . date( 'Y-m-d' ) . '.csv';
    $bulletins = BULLETINWP::instance()->export->get_export_data();

    wp_send_json_success( [
      'message'   => __( 'Exporting data complete.', BULLETINWP_PLUGIN_SLUG ),
      'filename'  => $filename,
      'bulletins' => $bulletins,
    ] );
  }

  /**
   * Import all bulletins
   *
   * @since 3.4.0
   *
   * @param void
   * @return void
   */
  public function bulletinwp_import_bulletins() {
    $bulletins = BULLETINWP::instance()->helpers->array_map_recursive( 'sanitize_text_field', $_POST['bulletins'] );

    if ( ! empty( $bulletins ) && BULLETINWP::instance()->import->import_csv_data( $bulletins ) ) {
      wp_send_json_success( [
        'message' => __( 'Importing data complete.', BULLETINWP_PLUGIN_SLUG ),
      ] );
    }

    wp_send_json_success( [
      'message' => __( 'Invalid csv data.', BULLETINWP_PLUGIN_SLUG ),
    ] );
  }
}
