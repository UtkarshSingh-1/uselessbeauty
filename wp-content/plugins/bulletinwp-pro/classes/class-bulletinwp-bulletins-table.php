<?php

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BULLETINWP_Bulletins_Table extends WP_List_Table {
  private static $menu_page_base_slug  = BULLETINWP_PLUGIN_SLUG . '-options';
  private static $bulletins_table_name = 'bulletinwp_bulletins';

  public function __construct() {
    parent::__construct( [
      'singular' => __( 'Bulletin', BULLETINWP_PLUGIN_SLUG ),
      'plural'   => __( 'Bulletins', BULLETINWP_PLUGIN_SLUG ),
      'ajax'     => false,
    ] );
  }

  /**
   * Message if bulletin results is empty
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function no_items() {
    echo __( 'No bulletins available.', BULLETINWP_PLUGIN_SLUG );
  }

  /**
   * Display bulletins on the table
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function prepare_items() {
    $user     = get_current_user_id();
    $screen   = get_current_screen();
    $option   = $screen->get_option( 'per_page', 'option' );
    $per_page = get_user_meta( $user, $option, true );

    if ( empty( $per_page ) || $per_page < 1 ) {
      $per_page = $screen->get_option( 'per_page', 'default' );
    }

    $current_page     = $this->get_pagenum();
    $total_items      = BULLETINWP::instance()->sql->get_all_bulletins_count();
    $columns          = $this->get_columns();
    $hidden_columns   = $this->get_hidden_columns();
    $sortable_columns = $this->get_sortable_columns();

    $this->set_pagination_args( [
      'total_items' => $total_items,
      'per_page'    => $per_page,
    ] );

    $this->_column_headers = [ $columns, $hidden_columns, $sortable_columns ];
    $this->process_bulk_action();
    $this->items = $this->get_bulletins( $per_page, $current_page );
  }

  /**
   * Get bulk actions options
   *
   * @param void
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_bulk_actions() {
    return [
      'activate'   => __( 'Activate', BULLETINWP_PLUGIN_SLUG ),
      'deactivate' => __( 'Deactivate', BULLETINWP_PLUGIN_SLUG ),
      'delete'     => __( 'Delete', BULLETINWP_PLUGIN_SLUG ),
    ];
  }

  /**
   * Get table columns
   *
   * @param void
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_columns() {
    $columns = [
      'cb'             => '<input type="checkbox" />',
      'bulletin_title' => __( 'Title', BULLETINWP_PLUGIN_SLUG ),
      'display_type'   => __( 'Display Type', BULLETINWP_PLUGIN_SLUG ),
    ];

    if ( bulletinwp_fs()->is__premium_only() ) {
      $columns['include_on'] = __( 'Include on', BULLETINWP_PLUGIN_SLUG );
      $columns['exclude_on'] = __( 'Exclude on ', BULLETINWP_PLUGIN_SLUG );
      $columns['user_rules'] = __( 'User rules', BULLETINWP_PLUGIN_SLUG );
    }

    $columns['status'] = __( 'Status', BULLETINWP_PLUGIN_SLUG );

    if ( bulletinwp_fs()->is__premium_only() ) {
      $columns['expiry'] = __( 'Expiry', BULLETINWP_PLUGIN_SLUG );
    }

    return $columns;
  }

  /**
   * Get table hidden columns
   *
   * @param void
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_hidden_columns() {
    return [
      'id'           => 'ID',
      'is_activated' => 'Activated',
    ];
  }

  /**
   * Get table sortable columns
   *
   * @param void
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_sortable_columns() {
    return [
      'bulletin_title' => [ 'bulletin_title', true ],
    ];
  }

  /**
   * Get all types of bulletins list
   *
   * @param void
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function get_views() {
    $views   = [];
    $current = ( ! empty( $_REQUEST['status'] ) ? sanitize_text_field( $_REQUEST['status'] ) : 'all' );

    // All
    $class        = ( $current == 'all' ? ' class="current"' : '' );
    $all_url      = remove_query_arg( 'status' );
    $views['all'] = "<a href='$all_url' $class>All (" . BULLETINWP::instance()->sql->get_all_bulletins_count() . ")</a>";

    // Active
    $active_url      = add_query_arg( 'status', 'active' );
    $class           = ( $current == 'active' ? ' class="current"' : '' );
    $views['active'] = "<a href='$active_url' $class>Active (" . BULLETINWP::instance()->sql->get_all_active_bulletins_count() . ")</a>";

    // Inactive
    $inactive_url      = add_query_arg( 'status', 'inactive' );
    $class             = ( $current == 'inactive' ? ' class="current"' : '' );
    $views['inactive'] = "<a href='$inactive_url' $class>Inactive (" . BULLETINWP::instance()->sql->get_all_inactive_bulletins_count() . ")</a>";

    return $views;
  }

  /**
   * Get bulletins data
   *
   * @param int $per_page
   * @param int $current_page
   *
   * @return array
   * @since 1.0.0
   *
   */
  private function get_bulletins( $per_page = 20, $current_page = 1 ) {
    global $table_prefix, $wpdb;

    $table_name = $table_prefix . self::$bulletins_table_name;
    $columns    = '`id`, `bulletin_title`, `is_activated`, `placement`';

    if ( bulletinwp_fs()->is__premium_only() ) {
      $columns .= ', placement_corner_options, placement_by_content, placement_selected_content_include, placement_selected_content_exclude, placement_by_user, placement_user_cookie_value, add_countdown, countdown, add_schedule, start_schedule';
    }

    $query = "SELECT $columns FROM $table_name";

    if ( ! empty( $_REQUEST['status'] ) ) {
      $status = sanitize_text_field( $_REQUEST['status'] );

      if ( strtolower( $status ) === 'active' ) {
        $query .= " WHERE is_activated = 1";
      } else {
        $query .= " WHERE is_activated = 0";
      }
    }

    if ( ! empty( $_REQUEST['orderby'] ) ) {
      $query .= ' ORDER BY ' . sanitize_sql_orderby( $_REQUEST['orderby'] );
      $query .= ! empty( $_REQUEST['order'] ) ? ' ' . sanitize_sql_orderby( $_REQUEST['order'] ) : ' ASC';
    } else {
      $query .= ' ORDER BY id DESC ';
    }

    $query    .= " LIMIT $per_page";
    $query    .= ' OFFSET ' . ( $current_page - 1 ) * $per_page;
    $bulletins = $wpdb->get_results( $query, 'ARRAY_A' );

    if ( ! empty( $bulletins ) && is_array( $bulletins ) ) {
      $simplified_bulletins_data = [];

      foreach ( $bulletins as $key => $bulletin ) {
        $display_type = '';
        $is_activated = $bulletin['is_activated'];
        $scheduled    = ( isset( $bulletin['add_schedule'] ) && $bulletin['add_schedule'] ) ? $bulletin['add_schedule'] : null;
        $status       = '<div class="checkbox-wrapper toggle-switch"
                              data-checked-label="'. __( 'Active', BULLETINWP_PLUGIN_SLUG ) .'"
                              data-unchecked-label="'. __( 'Inactive', BULLETINWP_PLUGIN_SLUG ) .'"
                              data-status-action="'. __( ( $is_activated ? 'deactivate' : 'activate' ), BULLETINWP_PLUGIN_SLUG ) .'"
                              data-bulletin-id="'. $bulletin['id'] .'"
                         >
                           <input type="checkbox" name="isActivated" '. __( ( $is_activated ? 'checked' : '' ), BULLETINWP_PLUGIN_SLUG ) .'/>
                           <span class="label">'. __( ( $is_activated ? 'Active' : 'Inactive' ), BULLETINWP_PLUGIN_SLUG ) .'</span>
                         </div>';

        if ( isset( $bulletin['placement'] ) ) {
          switch ( $bulletin['placement'] ) {
            case 'top':
              $display_type = 'Header';
              break;
            case 'sticky-footer':
              $display_type = 'Sticky Footer';
              break;
            case 'float-bottom':
              $display_type = 'Floating at bottom';
              break;
          }

          if ( bulletinwp_fs()->is__premium_only() ) {
            switch ( $bulletin['placement'] ) {
              case 'corner':
                $display_type = 'Corner';
                break;
            }

            if ( $bulletin['placement'] === 'corner' ) {
              $display_type = 'Corner - Top left';
              switch ( $bulletin['placement_corner_options'] ) {
                case 'top-left':
                  $display_type = 'Corner - Top left';
                  break;
                case 'top-right':
                  $display_type = 'Corner - Top right';
                  break;
                case 'bottom-left':
                  $display_type = 'Corner - Bottom left';
                  break;
                case 'bottom-right':
                  $display_type = 'Corner - Bottom right';
                  break;
              }
            }
          }
        }


        $simplified_bulletins_data[ $key ] = [
          'bulletin_title' => $bulletin['bulletin_title'],
          'display_type'   => $display_type,
        ];

        if ( bulletinwp_fs()->is__premium_only() ) {
          $include_on = 'All content';
          $exclude_on = 'None';
          $user_rules = 'Show for all';
          $expiry     = 'None';

          if ( isset( $bulletin['placement_by_content'] ) && $bulletin['placement_by_content'] === 'selected-content' ) {
            $includes = isset( $bulletin['placement_selected_content_include'] ) && is_serialized( $bulletin['placement_selected_content_include'] ) ? unserialize( $bulletin['placement_selected_content_include'] ) : [];
            $excludes = isset( $bulletin['placement_selected_content_exclude'] ) && is_serialized( $bulletin['placement_selected_content_exclude'] ) ? unserialize( $bulletin['placement_selected_content_exclude'] ) : [];

            if ( ! empty( $includes ) ) {
              $include_on = implode( ', ', $includes );
            }

            if ( ! empty( $excludes ) ) {
              $exclude_on = implode( ', ', $excludes );
            }
          }

          if ( isset( $bulletin['placement_by_user'] ) ) {
            switch ( $bulletin['placement_by_user'] ) {
              case 'everyone':
                $user_rules = 'Show for all';
                break;
              case 'logged-in-users':
                $user_rules = 'Only logged-in';
                break;
              case 'cookie-value':
                $cookie_value = ( isset( $bulletin['placement_user_cookie_value'] ) && ! empty( $bulletin['placement_user_cookie_value'] ) ) ? $bulletin['placement_user_cookie_value'] : '';
                $user_rules   = 'Cookie: <strong>' . $cookie_value . '</strong>';
                break;
            }
          }

          if ( isset( $bulletin['add_countdown'] ) && ! empty( $bulletin['add_countdown'] ) && ! empty( $bulletin['countdown'] ) ) {
            $expiry = gmdate( 'd M Y H:i', strtotime( $bulletin['countdown'] ) );
          }

          if ( isset( $bulletin['add_schedule'] ) && ! empty( $bulletin['add_schedule'] ) ) {
            $status = '<strong>Scheduled: </strong>' . gmdate( 'd M Y H:i', strtotime( $bulletin['start_schedule'] ) );
          }

          $simplified_bulletins_data[ $key ]['include_on'] = $include_on;
          $simplified_bulletins_data[ $key ]['exclude_on'] = $exclude_on;
          $simplified_bulletins_data[ $key ]['user_rules'] = $user_rules;
          $simplified_bulletins_data[ $key ]['expiry']     = $expiry;
        }

        $simplified_bulletins_data[ $key ]['status']       = $status;
        $simplified_bulletins_data[ $key ]['scheduled']    = $scheduled;
        $simplified_bulletins_data[ $key ]['id']           = $bulletin['id'];
        $simplified_bulletins_data[ $key ]['is_activated'] = $bulletin['is_activated'];
      }

      return array_values( $simplified_bulletins_data );
    }

    return [];
  }

  /**
   * Column cb display
   *
   * @param array $item
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function column_cb( $item ) {
    return sprintf(
      '<input type="checkbox" name="bulletin[]" value="%s" />',
      $item['id']
    );
  }

  /**
   * Column bulletin_title display
   *
   * @param array $item
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function column_bulletin_title( $item ) {
    $bulletin_title = $item['bulletin_title'];
    $is_activated   = $item['is_activated'];

    $edit_link = add_query_arg(
      [
        'page'     => self::$menu_page_base_slug . '-edit',
        'bulletin' => $item['id'],
      ],
      'admin.php'
    );

    $activate_link   = esc_url( wp_nonce_url( add_query_arg(
      [
        'page'     => self::$menu_page_base_slug,
        'action'   => 'activate',
        'bulletin' => $item['id'],
      ],
      'admin.php'
    ) ) );
    $deactivate_link = esc_url( wp_nonce_url( add_query_arg(
      [
        'page'     => self::$menu_page_base_slug,
        'action'   => 'deactivate',
        'bulletin' => $item['id'],
      ],
      'admin.php'
    ) ) );

    $delete_link = esc_url( wp_nonce_url( add_query_arg(
      [
        'page'     => self::$menu_page_base_slug,
        'action'   => 'delete',
        'bulletin' => $item['id'],
      ],
      'admin.php'
    ) ) );

    if ( bulletinwp_fs()->is__premium_only() ) {
      $duplicate_link = esc_url( wp_nonce_url( add_query_arg(
        [
          'page'     => self::$menu_page_base_slug,
          'action'   => 'duplicate',
          'bulletin' => $item['id'],
        ],
        'admin.php'
      ) ) );
    }

    $bulletin_link = BULLETINWP::instance()->helpers->get_bulletin_link( $item['id'] );

    if ( empty( $bulletin_title ) ) {
      $bulletin_title = '(no title)';
    }

    $title_status_class = 'title-status-' . $item['id'];

    $title_html = '<strong><a class="row-title" href="' . $edit_link . '">' . $bulletin_title . '</a> <span class="' . $title_status_class . '"> ' . ( ! $is_activated ? '&ndash;&nbsp;' . __( 'Inactive', BULLETINWP_PLUGIN_SLUG ) : '' ) . ' </span> </strong>';

    $actions = [
      'edit'          => '<a href="' . $edit_link . '">' . __( 'Edit', BULLETINWP_PLUGIN_SLUG ) . '</a>',
      'change_status' => '<a href="' . ( $item['is_activated'] ? $deactivate_link : $activate_link ) . '">' . ( $item['is_activated'] ? __( 'Deactivate', BULLETINWP_PLUGIN_SLUG ) : __( 'Activate', BULLETINWP_PLUGIN_SLUG ) ) . '</a>',
      'delete'        => '<a href="' . $delete_link . '">' . __( 'Delete', BULLETINWP_PLUGIN_SLUG ) . '</a>',
      'view'          => '<a href="' . $bulletin_link . '" target="_blank">' . ( $item['is_activated'] ? __( 'View', BULLETINWP_PLUGIN_SLUG ) : __( 'Preview', BULLETINWP_PLUGIN_SLUG ) ) . '</a>',
    ];

    if ( bulletinwp_fs()->is__premium_only() ) {
      $actions = array_merge( $actions, [
        'duplicate' => '<a href="' . $duplicate_link . '">' . __( 'Duplicate', BULLETINWP_PLUGIN_SLUG ) . '</a>',
      ] );

      if ( $item['scheduled'] ) {
        unset( $actions['change_status'] );
      }
    }

    return sprintf( '%1$s %2$s', $title_html, $this->row_actions( $actions ) );
  }

  /**
   * Default columns display
   *
   * @param array $item
   * @param string $column_name
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function column_default( $item, $column_name ) {
    if ( array_key_exists( $column_name, $item ) ) {
      return $item[ $column_name ];
    }

    return '';
  }

  /**
   * Get bulk actions options
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function process_bulk_action() {
    // Security check!
    if ( isset( $_GET['_wpnonce'] ) && ! empty( $_GET['_wpnonce'] ) && isset( $_GET['bulletin'] ) ) {
      $nonce  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
      $action = - 1;

      if ( is_array( $_GET['bulletin'] ) ) {
        $action = 'bulk-' . $this->_args['plural'];
      }

      if ( ! wp_verify_nonce( $nonce, $action ) ) {
        wp_die( 'Nope! Security check failed!' );
      }
    }

    $action = $this->current_action();

    if ( ! empty( $action ) ) {
      switch ( $action ) {
        case 'activate':
          if ( isset( $_GET['bulletin'] ) ) {
            if ( is_array( $_GET['bulletin'] ) ) {
              foreach ( $_GET['bulletin'] as $bulletin ) {
                $bulletin_id = sanitize_text_field( $bulletin );
                BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', true );
              }
            } elseif ( is_string( $_GET['bulletin'] ) ) {
              $bulletin_id = sanitize_text_field( $_GET['bulletin'] );
              BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', true );
            }
          }
          break;
        case 'deactivate':
          if ( isset( $_GET['bulletin'] ) ) {
            if ( is_array( $_GET['bulletin'] ) ) {
              foreach ( $_GET['bulletin'] as $bulletin ) {
                $bulletin_id = sanitize_text_field( $bulletin );
                BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', false );
              }
            } elseif ( is_string( $_GET['bulletin'] ) ) {
              $bulletin_id = sanitize_text_field( $_GET['bulletin'] );
              BULLETINWP::instance()->sql->update_bulletin_data( $bulletin_id, 'is_activated', false );
            }
          }
          break;
        case 'delete':
          if ( isset( $_GET['bulletin'] ) ) {
            if ( is_array( $_GET['bulletin'] ) ) {
              foreach ( $_GET['bulletin'] as $bulletin ) {
                $bulletin_id = sanitize_text_field( $bulletin );
                BULLETINWP::instance()->sql->delete_bulletin( $bulletin_id );
              }
            } elseif ( is_string( $_GET['bulletin'] ) ) {
              $bulletin_id = sanitize_text_field( $_GET['bulletin'] );
              BULLETINWP::instance()->sql->delete_bulletin( $bulletin_id );
            }
          }
          break;
        case 'duplicate':
          if ( bulletinwp_fs()->is__premium_only() ) {
            if ( isset( $_GET['bulletin'] ) ) {
              if ( is_array( $_GET['bulletin'] ) ) {
                foreach ( $_GET['bulletin'] as $bulletin ) {
                  $bulletin_id = sanitize_text_field( $bulletin );
                  BULLETINWP::instance()->pro->duplicate_bulletin( $bulletin_id );
                }
              } elseif ( is_string( $_GET['bulletin'] ) ) {
                $bulletin_id = sanitize_text_field( $_GET['bulletin'] );
                BULLETINWP::instance()->pro->duplicate_bulletin( $bulletin_id );
              }
            }
          }
          break;
        default:
          break;
      }
    }

    return;
  }
}
