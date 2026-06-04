<?php
/**
 * Class PSWC_Admin
 *
 * This PSWC Product Subtitle admin settings page
 *
 * @package PSWC_Admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class PSWC_Admin
 */
class PSWC_Admin {

	/**
	 * Flag to determine if subtitles are enabled.
	 *
	 * @var string
	 */
	private $enable_subtitle;

	/**
	 * Flag to determine if the subtitle column is enabled.
	 *
	 * @var string
	 */
	private $pswc_enable_wysiwyg;

	/**
	 * Flag to determine if the subtitle column is enabled.
	 *
	 * @var string
	 */
	private $pswc_enable_subtitle_col;

	/**
	 * Flag to determine if the subtitle column is enabled.
	 *
	 * @var string
	 */
	private $pswc_enable_admin_order_view;

	/**
	 * PSWC_Admin constructor.
	 */
	public function __construct() {
		$this->enable_subtitle    			= get_option( 'pswc_disable_subtitle', 'no' ); // Get the option for enabling subtitles.
		$this->pswc_enable_wysiwyg  		= get_option( 'pswc_enable_wysiwyg' ); // Get the option for enabling the WYSIWYG editor.
		$this->pswc_enable_subtitle_col 	= get_option( 'pswc_enable_subtitle_col' );
		$this->pswc_enable_admin_order_view = get_option( 'pswc_enable_admin_order_view' );

		$this->event_handler();
	}

	/**
	 * Event handler for actions and filters.
	 */
	public function event_handler() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );

		if ( 'yes' === $this->pswc_enable_subtitle_col ) :
			add_filter( 'manage_product_posts_columns', array( $this, 'add_product_subcolumn' ) );
			add_action( 'manage_product_posts_custom_column', array( $this, 'pswc_product_subcolumn' ), 10, 2 );
		endif;

		if ( 'yes' === $this->pswc_enable_admin_order_view ) :
			add_action( 'woocommerce_before_order_itemmeta', array( $this, 'subtitle_on_order' ), 10, 3 );
		endif;
		
		if ( 'no' === $this->enable_subtitle ) :
			add_action( 'edit_form_after_title', array( $this, 'subtitle_field' ) ); // Add action to display custom subtitle field.
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'guternburg_subtitle_field' ) ); // Add action to save subtitle field.
			add_action( 'save_post', array( $this, 'save_pswc_subtitle_field' ) ); // Add action to save subtitle field.
			
		endif;
	}

	/**
	 * Add product subtitle column.
	 *
	 * @param  array $columns Columns array.
	 * @return array
	 */
	public function add_product_subcolumn( $columns ) {
		$columns['product_subtitle'] = esc_html__( 'Product Subtitle', 'product-subtitle-for-woocommerce' );
		return $columns;
	}

	/**
	 * Output product subtitle in column.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function pswc_product_subcolumn( $column, $post_id ) {
		if ( 'product_subtitle' === $column ) {
			$product_subtitle = get_post_meta( $post_id, 'pswc_subtitle', true );
			echo wp_kses_post( $product_subtitle );
		}
	}

	/**
	 * is guternburg editor
	 */	 
	private function is_gutenberg_editor() {
		// Check if the current screen is using the block editor
		if (function_exists('is_gutenberg_page')) {
			return is_gutenberg_page();
		}

		$current_screen = get_current_screen();
		return $current_screen && method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor();
	}

	/**
	 * Display product subtitle on order item.
	 *
	 * @param int    $item_id Order item ID.
	 * @param object $item    Order item object.
	 * @param object $product Product object.
	 */
	public function subtitle_on_order( $item_id, $item, $product ) {
		if ( $product ) :
			$subtitle = $product->get_meta( 'pswc_subtitle' );
			if ( $subtitle ) :
				printf(
					'<div class="product-subtitle">%s</div>',
					wp_kses_post( $subtitle )
				);
			endif;
		endif;
	}

	/**
	 * Enqueue the custom CSS file in the admin area
	 */
	public function enqueue_admin_styles() {
		// Enqueue the CSS file.
		wp_enqueue_style( 'admin-style', PSWC_URL . 'assets/css/admin-style.css', array(), PSWC_VERSION, 'all' );
	}
	
	/**
	 * Subtitle field on edit product page
	 */
	public function subtitle_field() {
		global $post;

		if ( 'product' === $post->post_type ) :
			$pswc_subtitle = get_post_meta( $post->ID, 'pswc_subtitle', true ); ?>
			<div class="pswc-pub-section">
				<h2 for="pswc_subtitle_editor" style="padding: 10px 0;"><?php esc_html_e( 'Product Sub Title', 'product-subtitle-for-woocommerce' ); ?></h2>

				<?php
				if ( 'yes' === $this->pswc_enable_wysiwyg ) :
					$settings  = array(
						'textarea_name' => 'pswc_subtitle', // Set the name of the textarea.
						'textarea_rows' => 5, // Set the number of rows for the textarea.
						'teeny'         => true, // Enable the "teeny" mode for the editor.
					);
					wp_editor( $pswc_subtitle, 'pswc_subtitle_editor', $settings ); // Display the WYSIWYG editor.
				else :
					?>
					<input type="text" id="pswc_subtitle" name="pswc_subtitle" value="<?php echo esc_attr( $pswc_subtitle ); ?>" style="width:100%" placeholder="Enter Your Product Subtitle" />
				<?php endif; ?>
			</div>
			<?php
		endif;
	}
	
	/**
	 * guternburg subtitle field
	 */
	public function guternburg_subtitle_field(){
		if ( ! $this->is_gutenberg_editor() ) return;
		$this->subtitle_field();
	}

	/**
	 * Save subtitle field value
	 *
	 * @param int $product_id Product ID.
	 */
	public function save_pswc_subtitle_field( $product_id ) {
		// Check the nonce.
		$security = isset( $_POST['woocommerce_meta_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ) : '';
		if ( empty( $security ) || ! wp_verify_nonce( $security, 'woocommerce_save_data' ) ) : // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		endif;

		if ( isset( $_POST['post_type'] ) && 'product' === $_POST['post_type'] ) :
			if ( isset( $_POST['pswc_subtitle'] ) ) :
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$pswc_subtitle = wp_kses_post( wp_unslash( $_POST['pswc_subtitle'] ) );
				update_post_meta( $product_id, 'pswc_subtitle', $pswc_subtitle );
			endif;
		endif;
	}
}

new PSWC_Admin();
