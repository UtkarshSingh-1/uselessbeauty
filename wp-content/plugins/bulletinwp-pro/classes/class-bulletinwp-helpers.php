<?php

defined( 'ABSPATH' ) or exit;

class BULLETINWP_Helpers {
  /**
   * Get the plugin file path relative to the plugins directory.
   *
   * @param void
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function get_plugin_file_path() {
    $plugin_url = plugin_dir_url( BULLETINWP__FILE__ );

    if ( preg_match( "/\/([^\/]+)\/?$/", $plugin_url, $matches ) ) {
      $plugin_slug = BULLETINWP_PLUGIN_SLUG;

      return "{$matches[1]}/{$plugin_slug}.php";
    }

    return '';
  }

  /**
   * Get the site's timezone string
   *
   * @param void
   *
   * @return string
   * @since 2.5.1
   *
   */
  public function get_timezone_string() {
    return get_option( 'timezone_string' );
  }

  /**
   * Get bulletin link
   *
   * @param string $bulletin_id
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function get_bulletin_link( $bulletin_id ) {
    if ( ! BULLETINWP::instance()->sql->maybe_get_bulletin( $bulletin_id ) ) {
      return false;
    }

    $bulletin_link = get_site_url();

    if ( bulletinwp_fs()->is__premium_only() ) {
      $bulletin = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, [ 'placement_by_content', 'placement_selected_content_include' ] );

      if ( ! empty( $bulletin ) ) {
        if ( isset( $bulletin['placement_by_content'] ) ) {
          if ( $bulletin['placement_by_content'] === 'selected-content' ) {
            $includes = isset( $bulletin['placement_selected_content_include'] ) && is_serialized( $bulletin['placement_selected_content_include'] ) ? unserialize( $bulletin['placement_selected_content_include'] ) : [];
            $includes = array_values( $includes );

            if ( ! empty( $includes ) ) {
              $first_include = $includes[0];
              $path          = $first_include;

              if ( strpos( $path, '(.*)' ) !== false ) {
                $path = str_replace( '(.*)', '', $path );
              }

              $bulletin_link = get_site_url( null, $path );
            }
          }
        }
      }
    }

    $is_activated = BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, 'is_activated' );

    if ( ! $is_activated ) {
      $bulletin_link = add_query_arg( [
        'bulletin'     => $bulletin_id,
        'preview-mode' => 'true',
      ], $bulletin_link );
    }

    return $bulletin_link;
  }

  /**
   * Check if the bulletin is in preview mode
   *
   * @param void
   *
   * @return bool
   * @since 1.0.0
   *
   */
  public function maybe_in_preview_mode() {
    if ( is_user_logged_in()
         && ! empty( $_GET['bulletin'] )
         && ! empty( $_GET['preview-mode'] )
         && sanitize_text_field( $_GET['preview-mode'] ) === 'true'
    ) {
      $bulletin_id = sanitize_text_field( $_GET['bulletin'] );

      return BULLETINWP::instance()->sql->maybe_get_bulletin( $bulletin_id );
    }

    return false;
  }

  /**
   * Get bulletin id in preview mode
   *
   * @param void
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function get_preview_mode_bulletin_id() {
    if ( $this->maybe_in_preview_mode() ) {
      return sanitize_text_field( $_GET['bulletin'] );
    }

    return '';
  }

  /**
   * Array map
   *
   * @param mixed $function
   * @param array $array
   *
   * @return array
   * @since 1.0.0
   *
   */
  public function array_map_recursive( $function, $array ) {
    $new_array = [];

    foreach ( $array as $key => $value ) {
      $new_array_key_value = '';

      if ( is_array( $value ) ) {
        $new_array_value = $this->array_map_recursive( $function, $value );
      } elseif ( is_array( $function ) ) {
        $new_array_key_value = call_user_func_array( $function, $value );
      } elseif ( is_callable( $function ) ) {
        $new_array_value = $function( $value );
      }

      $new_array[ $key ] = $new_array_value;
    }

    return $new_array;
  }

  /**
   * Load module
   *
   * @param string $dir
   *
   * @return void
   * @since 3.6.0
   *
   */
  public function load_module( $dir ) {
    $composer   = json_decode( file_get_contents( "$dir/composer.json" ), 1 );
    $namespaces = $composer['autoload']['psr-4'];

    foreach ( $namespaces as $namespace => $classpaths ) {
      if ( ! is_array( $classpaths ) ) {
        $classpaths = array( $classpaths );
      }

      spl_autoload_register( function ( $classname ) use ( $namespace, $classpaths, $dir ) {
        if ( preg_match( "#^" . preg_quote( $namespace ) . "#", $classname ) ) {
          $classname = str_replace( $namespace, "", $classname );
          $filename  = preg_replace( "#\\\\#", "/", $classname ) . ".php";
          foreach ( $classpaths as $classpath ) {
            $fullpath = $dir . "/" . $classpath . "/$filename";
            if ( file_exists( $fullpath ) ) {
              include_once $fullpath;
            }
          }
        }
      } );
    }
  }

  /**
   * Get compressed HTML string
   *
   * @param string $html_string
   *
   * @return string
   * @since 1.0.0
   *
   */
  public function get_compressed_html_string( $html_string ) {
    return preg_replace(
      array(
        '/<!--(.*?)-->/s',  // delete HTML comments
        '@\/\*(.*?)\*\/@s', // delete JavaScript comments
        /* Fix HTML */
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/\>\s+\</',     // strip whitespaces between tags
      ),
      array(
        '', // delete HTML comments
        '', // delete JavaScript comments
        /* Fix HTML */
        '> ',  // strip whitespaces after tags, except space
        ' <',  // strip whitespaces before tags, except space
        '> <', // strip whitespaces between tags
      ),
      $html_string
    );
  }

  /**
   * Get compressed CSS string
   *
   * @param string $css_string
   *
   * @return string
   * @since 2.0.0
   *
   */
  public function get_compressed_css_string( $css_string ) {
    if ( trim( $css_string ) === "" ) {
      return $css_string;
    }

    return preg_replace(
      array(
        // Remove comment(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
        // Remove unused white-space(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
          // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
          '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
          // Replace `:0 0 0 0` with `:0`
          '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
          // Replace `background-position:0` with `background-position:0 0`
          '#(background-position):0(?=[;\}])#si',
          // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
          '#(?<=[\s:,\-])0+\.(\d+)#s',
          // Minify string value
          '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
          '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
          // Minify HEX color code
          '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
          // Replace `(border|outline):none` with `(border|outline):0`
          '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
          // Remove empty selector(s)
          '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
      array(
        '$1',
        '$1$2$3$4$5$6$7',
        '$1',
        ':0',
        '$1:0 0',
        '.$1',
        '$1$3',
        '$1$2$4$5',
        '$1$2$3',
        '$1:0',
        '$1$2'
      ),
      $css_string
    );
  }


  /**
   * Get kses protocol for including svgs
   *
   * @param void
   *
   * @return Array
   * @since 3.5.2
   *
   */
  public function get_kses_allowed_protocols_for_svg() {
    return [
      'svg' => [
        'xmlns'       => [],
        'xmlns:xlink' => [],
        'viewbox'     => [],
        'width'       => [],
        'height'      => [],
        'version'     => []
      ],
      'path' => [
        'class' => [],
        'd'     => [],
        'id'    => []
      ],
      'g' => [
        'id'           => [],
        'stroke'       => [],
        'stroke-width' => [],
        'fill'         => [],
        'fill-rule'    => []
      ],
    ];
  }
}
