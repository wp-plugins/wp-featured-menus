<?php
/**
 * Plugin Name: WP Featured Menus
 * Plugin URI: http://codeventure.net
 * Description: Provides a mechanism for associating a WordPress Menu with a Page or Post
 * Author: Topher
 * Version: 1.0
 * Author URI: http://codeventure.net
 * Text Domain: wp-featured-menus
 */


/**
 * Provides a mechanism for associating a WordPress Menu with a Page or Post
 *
 * @package T1K_Featured_Menus
 * @since T1K_Featured_Menus 1.0
 * @author Topher
 */


/**
 * Instantiate the T1K_Featured_Menus instance
 * @since T1K_Featured_Menus 1.0
 */
add_action( 'plugins_loaded', array( 'T1K_Featured_Menus', 'instance' ) );

/**
 * Main T1K Featured Menus Class
 *
 * Contains the main functions for the admin side of T1K Featured Menus
 *
 * @class T1K_Featured_Menus
 * @version 1.0.0
 * @since 1.0
 * @package T1K_Featured_Menus
 * @author Topher
 */
class T1K_Featured_Menus {

	/**
	* Instance handle
	*
	* @static
	* @since 1.2
	* @var string
	*/
	private static $__instance = null;

	/**
	 * T1K_Featured_Menus Constructor, actually contains nothing
	 *
	 * @access public
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Instance initiator, runs setup etc.
	 *
	 * @access public
	 * @return self
	 */
	public static function instance() {
		if ( ! is_a( self::$__instance, __CLASS__ ) ) {
			self::$__instance = new self;
			self::$__instance->setup();
		}
		
		return self::$__instance;
	}

	/**
	 * Runs things that would normally be in __construct
	 *
	 * @access private
	 * @return void
	 */
	private function setup() {

		// only do this in the admin area
		if ( is_admin() ) {
			add_action( 'save_post', array( $this, 'save' ) );
			add_action( 'add_meta_boxes', array( $this, 'menu_meta_box' ) );
		}

	}

	/**
	 * Make meta box holding select menu of Menus
	 *
	 * @access public
	 * @return void
	 */
	public function menu_meta_box( $post_type ) {

		// limit meta box to certain post types
		$post_types = array( 'post', 'page' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'wp-fetaured-menu',
				esc_html__( 'Featured Menu', 'wp-featured-menus' ),
				array( $this, 'render_menu_meta_box_contents' ),
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Render select box of WP Menus
	 *
	 * @access public
	 * @return void
	 */
	public function render_menu_meta_box_contents() {

		global $post;

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wp-featured-menus', 'wp_featured_menus_nonce' );

		// go get the meta field
		$wpfm_meta_value = get_post_meta( $post->ID, '_t1k_featured_menu', true );

		// Display the form, using the current value.

		echo '<p>';
		esc_html_e( 'Please choose from the existing menus below.  If you need to create a new Menu, please go to ', 'wp-featured-menus' );
		echo '<a href="' . admin_url( 'nav-menus.php' ) . '">';
		esc_html_e( 'the Menu Admin ', 'wp-featured-menus' );
		echo '</a>.';
		echo '</p>';


		// go get the Menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );

		// make sure we have some
		if ( count( $menus ) > 0 ) {
			echo '<select name="_t1k_featured_menu">' . "\n";
			echo '<option value="">' . __( 'Please choose', 'wp-featured-menus' ) . '</option>' . "\n";
			foreach ( $menus as $key => $menu ) {
				$selected = selected( $wpfm_meta_value, $menu->term_id, false );
				echo '<option value="' . absint( $menu->term_id ) . '"' . $selected . '>' . esc_html( $menu->name ) . '</option>' . "\n";
			}
			echo '</select>' . "\n";
		} else {
			echo '<p>';
			esc_html_e( 'No menus found, ', 'wp-featured-menus' );
			echo '<a href="' . admin_url( 'nav-menus.php' ) . '">';
			esc_html_e( 'let\'s go make some!', 'wp-featured-menus' );
			echo '</a></p>';
		}

	}

	/**
	 * Updates the options table with the form data
	 *
	 * @access public
	 * @param int $post_id
	 * @return void
	 */
	public function save( $post_id ) {

		// Check if the current user is authorised to do this action. 
		$post_type = get_post_type_object( get_post( $post_id )->post_type );
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

		// Check if the user intended to change this value.
		if ( ! isset( $_POST['wp_featured_menus_nonce'] ) || ! wp_verify_nonce( $_POST['wp_featured_menus_nonce'], 'wp-featured-menus' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}


		// Sanitize user input
		$wp_featured_menu = sanitize_text_field( $_POST['_t1k_featured_menu'] );

		// Update or create the key/value
		update_post_meta( $post_id, '_t1k_featured_menu', $wp_featured_menu );

	}

	// end class
}

?>
