<?php

defined( 'ABSPATH' ) or exit;

class BULLETINWP_Pro {
  private static $bulletins_table_name = 'bulletinwp_bulletins';
  private static $options_table_name   = 'bulletinwp_options';

  public function __construct() {
    add_action( 'plugins_loaded', array( $this, 'insert_preset_data_allowed_role' ) );
    add_action( BULLETINWP_PLUGIN_SLUG . '_set_start_date_bulletin_schedule', array( $this, 'set_start_date_bulletin_schedule' ), 10, 1 );
    add_action( BULLETINWP_PLUGIN_SLUG . '_set_end_date_bulletin_schedule', array( $this, 'set_end_date_bulletin_schedule' ), 10, 1 );
  }

  /**
   * Check page access permission
   *
   * @since 2.5.0
   *
   * @param void
   * @return void
   */
  public function check_page_access_permission() {
    // Check user role access
    $allow_users_to_manage_bulletins = BULLETINWP::instance()->sql->get_option( 'allow_users_to_manage_bulletins' );

    if ( ! empty( $allow_users_to_manage_bulletins ) ) {
      if ( current_user_can( 'edit_pages' ) || current_user_can( 'manage_options' ) ) {
        foreach ( $allow_users_to_manage_bulletins as $allow_user ) {
          if ( ! is_numeric( $allow_user['allow_user'] ) ) {
            if ( current_user_can( $allow_user['allow_user'] ) ) {
              return true;
            }
          } else {
            if ( $allow_user['allow_user']  == get_current_user_id() || current_user_can( 'manage_options' ) ) {
              return true;
            }
          }
        }
      } else {
        return false;
      }
    } elseif ( current_user_can( 'manage_options' ) ) {
      return true;
    }

    return false;
  }

  /**
   * Check if request uri is in or not in an array of paths
   *
   * @param array $array
   * @param bool $in_array
   *
   * @return bool
   * @since 1.0.0
   *
   */
  private function maybe_request_uri_is_in_array( $array, $in_array = true ) {
    if ( ! empty( $array ) ) {
      $request_uri      = $_SERVER['REQUEST_URI'];
      $request_uri_arr  = parse_url( $request_uri );
      $request_uri_path = $request_uri_arr['path'];
      $request_uri_path = rtrim( $request_uri_path, '/' );
      $request_uri      = $request_uri_path;

      if ( isset( $request_uri_arr['query'] ) ) {
        $request_uri = "{$request_uri}/?{$request_uri_arr['query']}";
      }

      foreach ( $array as $path ) {
        $request_uri_with_trailing_slash    = rtrim( $request_uri, '/' ) . '/';
        $request_uri_without_trailing_slash = rtrim( $request_uri, '/' );

        if ( strpos( $path, '(.*)' ) !== false ) {
          $regex_string = str_replace( "/", "\\/", $path );
          $regex_string = str_replace( "?", "\\?", $regex_string );

          if ( preg_match( "/^$regex_string$/", $request_uri_with_trailing_slash )
               || preg_match( "/^$regex_string$/", $request_uri_without_trailing_slash )
          ) {
            return $in_array;
          }
        } else {
          $path_arr = parse_url( $path );
          $path     = $path_arr['path'];
          $path     = rtrim( $path, '/' );

          if ( isset( $path_arr['query'] ) ) {
            $path = "{$path}/?{$path_arr['query']}";
          }

          if ( in_array( $path, [ $request_uri_with_trailing_slash, $request_uri_without_trailing_slash ] ) ) {
            return $in_array;
          }
        }
      }
    }

    return ! $in_array;
  }

  /**
   * Check if request uri is in includes
   *
   * @param array $includes
   *
   * @return bool
   * @since 1.0.0
   *
   */
  private function maybe_request_uri_is_in_includes( $includes = [] ) {
    return $this->maybe_request_uri_is_in_array( $includes );
  }

  /**
   * Check if request uri is not in excludes
   *
   * @param array $excludes
   *
   * @return bool
   * @since 1.0.0
   *
   */
  private function maybe_request_uri_is_not_in_excludes( $excludes = [] ) {
    return $this->maybe_request_uri_is_in_array( $excludes, false );
  }

  /**
   * Get all allowed active bulletins
   *
   * @param string $placement
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_all_allowed_active_bulletins( $placement = '', $corner_position = '' ) {
    global $table_prefix, $wpdb;

    $is_activated = 1;
    $bulletins    = [];

    if ( is_multisite() && ! is_main_site() ) {
      $table_name         = $wpdb->base_prefix . self::$bulletins_table_name;
      $apply_all_subsites = 1;
      $query              = $wpdb->prepare( "SELECT * FROM $table_name WHERE is_activated = %d AND apply_all_subsites = %d", $is_activated, $apply_all_subsites );

      if ( ! empty( $placement ) ) {
        $query .= $wpdb->prepare( " AND placement = %s", $placement );
      }

      if ( $placement === 'corner' && ! empty( $corner_position ) ) {
        $query .= $wpdb->prepare( " AND placement_corner_options = %s", $corner_position );
      }

      $results   = $wpdb->get_results( $query, 'ARRAY_A' );
      $bulletins = array_values( array_merge( $bulletins, $results ) );
      $bulletins = array_map(
        function ( $bulletin ) {
          $bulletin['id'] = 'global-' . $bulletin['id'];

          return $bulletin;
        },
        $bulletins
      );
    }

    $table_name = $table_prefix . self::$bulletins_table_name;
    $query      = $wpdb->prepare( "SELECT * FROM $table_name WHERE is_activated = %d", $is_activated );

    if ( ! empty( $placement ) ) {
      $query .= $wpdb->prepare( " AND placement = %s", $placement );
    }

    if ( $placement === 'corner' && ! empty( $corner_position ) ) {
      $query .= $wpdb->prepare( " AND placement_corner_options = %s", $corner_position );
    }

    $results                  = $wpdb->get_results( $query, 'ARRAY_A' );
    $bulletins                = array_values( array_merge( $bulletins, $results ) );
    $allowed_active_bulletins = [];

    if ( ! empty( $bulletins ) ) {
      foreach ( $bulletins as $bulletin ) {
        $is_bulletin_allowed_by_content = false;
        $is_bulletin_allowed_by_user    = false;

        // Placement by content
        if ( isset( $bulletin['placement_by_content'] ) ) {
          if ( $bulletin['placement_by_content'] === 'selected-content' ) {
            $includes = isset( $bulletin['placement_selected_content_include'] ) && is_serialized( $bulletin['placement_selected_content_include'] ) ? unserialize( $bulletin['placement_selected_content_include'] ) : [];
            $excludes = isset( $bulletin['placement_selected_content_exclude'] ) && is_serialized( $bulletin['placement_selected_content_exclude'] ) ? unserialize( $bulletin['placement_selected_content_exclude'] ) : [];

            if ( ! empty( $includes ) && ! empty( $excludes ) ) {
              $is_bulletin_allowed_by_content = $this->maybe_request_uri_is_in_includes( $includes ) && $this->maybe_request_uri_is_not_in_excludes( $excludes );
            } elseif ( ! empty( $includes ) ) {
              $is_bulletin_allowed_by_content = $this->maybe_request_uri_is_in_includes( $includes );
            } elseif ( ! empty( $excludes ) ) {
              $is_bulletin_allowed_by_content = $this->maybe_request_uri_is_not_in_excludes( $excludes );
            } else {
              $is_bulletin_allowed_by_content = true;
            }
          } else {
            $is_bulletin_allowed_by_content = true;
          }
        } else {
          $is_bulletin_allowed_by_content = true;
        }

        // Placement by user
        if ( isset( $bulletin['placement_by_user'] ) ) {
          if ( $bulletin['placement_by_user'] === 'cookie-value' ) {
            if ( isset( $bulletin['placement_user_cookie_value'] ) && ! empty( $bulletin['placement_user_cookie_value'] ) ) {
              if ( isset( $_COOKIE[ $bulletin['placement_user_cookie_value'] ] ) && $_COOKIE[ $bulletin['placement_user_cookie_value'] ] ) {
                $is_bulletin_allowed_by_user = true;
              }
            }
          } elseif ( $bulletin['placement_by_user'] === 'logged-in-users' ) {
            if ( is_user_logged_in() ) {
              $is_bulletin_allowed_by_user = true;
            }
          } else {
            $is_bulletin_allowed_by_user = true;
          }
        } else {
          $is_bulletin_allowed_by_user = true;
        }

        if ( $is_bulletin_allowed_by_content && $is_bulletin_allowed_by_user ) {
          $allowed_active_bulletins[] = $bulletin;
        }
      }
    }

    return array_values( $allowed_active_bulletins );
  }

  /**
   * Check if google fonts API is needed to import
   *
   * @param void
   *
   * @return bool
   * @since 1.0.0
   *
   */
  public function is_google_fonts_api_needed() {
    if ( BULLETINWP::instance()->helpers->maybe_in_preview_mode() ) {
      $bulletin_id = BULLETINWP::instance()->helpers->get_preview_mode_bulletin_id();
      $bulletin    = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, [ 'fonts', 'google_fonts' ] );

      return ( isset( $bulletin['fonts'] ) && $bulletin['fonts'] === 'google-fonts' && isset( $bulletin['google_fonts'] ) && ! empty( $bulletin['google_fonts'] ) );
    } elseif ( ! empty( $allowed_active_bulletins = $this->get_all_allowed_active_bulletins() ) ) {
      $bulletins_with_google_fonts = array_filter( $allowed_active_bulletins, function ( $bulletin ) {
        return ( isset( $bulletin['fonts'] ) && $bulletin['fonts'] === 'google-fonts' && isset( $bulletin['google_fonts'] ) && ! empty( $bulletin['google_fonts'] ) );
      } );

      return ! empty( $bulletins_with_google_fonts );
    }

    return false;
  }

  /**
   * Check if swiper lib is needed to import
   *
   * @param void
   *
   * @return bool
   * @since 1.0.0
   *
   */
  public function is_swiper_lib_needed() {
    if ( BULLETINWP::instance()->helpers->maybe_in_preview_mode() ) {
      $bulletin_id = BULLETINWP::instance()->helpers->get_preview_mode_bulletin_id();
      $bulletin    = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, [ 'is_multiple_messages', 'rotation_style' ] );

      return ( isset( $bulletin['is_multiple_messages'] ) && $bulletin['is_multiple_messages'] && isset( $bulletin['rotation_style'] ) && $bulletin['rotation_style'] === 'cycle' );
    } elseif ( ! empty( $allowed_active_bulletins = $this->get_all_allowed_active_bulletins() ) ) {
      $bulletins_with_swiper = array_filter( $allowed_active_bulletins, function ( $bulletin ) {
        return ( isset( $bulletin['is_multiple_messages'] ) && $bulletin['is_multiple_messages'] && isset( $bulletin['rotation_style'] ) && $bulletin['rotation_style'] === 'cycle' );
      } );

      return ! empty( $bulletins_with_swiper );
    }

    return false;
  }

  /**
   * Get all displayed bulletins google fonts
   *
   * @param void
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_all_displayed_bulletins_google_fonts() {
    if ( BULLETINWP::instance()->helpers->maybe_in_preview_mode() ) {
      $bulletin_id = BULLETINWP::instance()->helpers->get_preview_mode_bulletin_id();
      $bulletin    = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, [ 'fonts', 'google_fonts' ] );

      if ( isset( $bulletin['fonts'] ) && $bulletin['fonts'] === 'google-fonts' && isset( $bulletin['google_fonts'] ) && ! empty( $bulletin['google_fonts'] ) ) {
        return [ $bulletin['google_fonts'] ];
      }
    } elseif ( ! empty( $allowed_active_bulletins = $this->get_all_allowed_active_bulletins() ) ) {
      $bulletins_with_google_fonts = array_filter( $allowed_active_bulletins, function ( $bulletin ) {
        return ( isset( $bulletin['fonts'] ) && $bulletin['fonts'] === 'google-fonts' && isset( $bulletin['google_fonts'] ) && ! empty( $bulletin['google_fonts'] ) );
      } );

      return array_values( array_unique( wp_list_pluck( $bulletins_with_google_fonts, 'google_fonts' ) ) );
    }

    return [];
  }

  /**
   * Insert preset data allowed role option table
   *
   * @param void
   *
   * @return void
   * @since 1.0.6
   *
   */
  public function insert_preset_data_allowed_role() {
    $selector    = 'allow_users_to_manage_bulletins';
    if ( BULLETINWP::instance()->sql->maybe_get_option( $selector ) ) {
      return;
    }

    global $table_prefix, $wpdb;

    $table_name  = $table_prefix . self::$options_table_name;
    $arr_value   = [];

    if ( ! BULLETINWP::instance()->sql->maybe_get_option( $selector ) ) {
      $arr_value[] = [
        'allow_user' => 'administrator',
      ];

      $value = array_values( $arr_value );
      $value = serialize( $arr_value );

      $wpdb->insert( $table_name, [
        'meta_key'   => $selector,
        'meta_value' => $value,
      ] );
    }
  }

  /**
   * Duplicate bulletin feature
   *
   * @param array
   *
   * @return void
   * @since 2.1.1
   *
   */
  public function duplicate_bulletin( $bulletin_id ) {
    if ( empty( $bulletin_id ) ) {
      return;
    }

    global $table_prefix, $wpdb;

    $table_name = $wpdb->base_prefix . self::$bulletins_table_name;
    $bulletin   = BULLETINWP::instance()->sql->get_bulletin( $bulletin_id );

    if ( ! empty( $bulletin ) ) {
      $bulletin = array_merge( $bulletin, [
        'id'           => '',
        'is_activated' => '',
      ] );

      $wpdb->insert( $table_name, $bulletin );

      return $bulletin;
    }
  }

  /**
   * Check user permission
   *
   * @param void
   *
   * @return boolean
   * @since 2.2.0
   *
   */
  public function check_user_permission() {
    // Check user role access

    $allow_users_to_manage_bulletins = BULLETINWP::instance()->sql->get_option( 'allow_users_to_manage_bulletins' );

    if ( ! empty( $allow_users_to_manage_bulletins ) ) {
      if ( current_user_can( 'edit_pages' ) || current_user_can( 'manage_options' ) ) {
        foreach ( $allow_users_to_manage_bulletins as $allow_user ) {
          if ( ! is_numeric( $allow_user['allow_user'] ) ) {
            if ( current_user_can( $allow_user['allow_user'] ) ) {
              return true;
            }
          }
        }
      } else {
        return false;
      }
    } else {
      if ( current_user_can( 'manage_options' ) ) {
        return true;
      }
    }

    return;
  }

  /**
   * Set start date bulletin schedule
   *
   * @param string $bulletin_id
   *
   * @return void
   * @since 3.5.0
   *
   */
  public function set_start_date_bulletin_schedule( $bulletin_id ) {
    if ( ! empty( $bulletin_id ) ) {
      $bulletin = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, [ 'add_schedule', 'start_schedule' ] );

      if ( ! empty( $bulletin )
           && isset( $bulletin['add_schedule'] )
           && $bulletin['add_schedule']
           && isset( $bulletin['start_schedule'] )
           && $bulletin['start_schedule']
      ) {
        BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', true );
        BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'add_schedule', false );
        BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'start_schedule', '' );
      }
    }
  }

  /**
   * Set end date bulletin schedule
   *
   * @param string $bulletin_id
   *
   * @return void
   * @since 3.5.0
   *
   */
  public function set_end_date_bulletin_schedule( $bulletin_id ) {
    if ( ! empty( $bulletin_id ) ) {
      $bulletin = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, [ 'add_countdown', 'countdown' ] );

      if ( ! empty( $bulletin )
           && isset( $bulletin['add_countdown'] )
           && $bulletin['add_countdown']
           && isset( $bulletin['countdown'] )
           && $bulletin['countdown']
      ) {
        BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', false );
        BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'add_countdown', false );
        BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'countdown', '' );
      }
    }
  }

  /**
   * Check if Easy Popups plugin is activated
   *
   * @since 3.6.0
   *
   * @param void
   * @return bool
   *
   */
  public function maybe_easy_popups_plugin_is_activated() {
    return class_exists( 'EASY_POPUPS' );
  }
}
