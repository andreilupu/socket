<?php
/**
 * Plugin Name:       Admin Options
 * Plugin URI:        https://andrei-lupu.com/
 * Description:       admin
 * Version:           1.1.0
 * Author:            Pixelgrade
 * Author URI:        https://andrei-lupu.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       adminoptions
 * Domain Path:       /languages
 */


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gridable
 * @subpackage Gridable/admin
 * @author     Pixelgrade <contact@pixelgrade.com>
 */
class Gridable_Admin_Page {
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $name;

	private $options;

	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
		$this->name = esc_html__( 'Options Page Name', 'adminoptions' );

		$this->config = array(

			'tab1' => array(
				'label'  => 'Tab title',
				'fields' => array(
					'texter' => array(
						'label'   => 'Example',
						'type'    => 'text',
						'default' => 'whaaadasa sad as das'
					),

					'textera' => array(
						'label'   => 'Text area Example',
						'type'    => 'textarea',
						'default' => 'whaaadasa sad as das'
					),

					'checkb' => array(
						'label'   => 'Text checkbox Example',
						'type'    => 'checkbox',
						'default' => 'whaaadasa sad as das'
					),

					'editor' => array(
						'label'   => 'Example',
						'type'    => 'editor',
						'default' => 'whaaadasa sad as das'
					)
				)
			),

			'tab2' => array(
				'label'  => 'Second tab title',
				'fields' => array(
					'toogler' => array(
						'label'   => 'Checkbox Example',
						'type'    => 'checkbox',
						'default' => 'whaaadasa sad as das'
					),

					'select' => array(
						'label'   => 'Select Example',
						'type'    => 'select',
						'default' => 'whaaadasa sad as das'
					),

					'radio' => array(
						'label'   => 'Example',
						'type'    => 'radio',
						'default' => 'whaaadasa sad as das'
					),
				)
			),

			'tab3' => array(
				'label'  => '3 tab title',
				'fields' => array(
					'toogler' => array(
						'label'   => 'Checkbox Example',
						'type'    => 'checkbox',
						'default' => 'whaaadasa sad as das'
					),

					'select' => array(
						'label'   => 'Select Example',
						'type'    => 'select',
						'default' => 'whaaadasa sad as das'
					),

					'radio' => array(
						'label'   => 'Example',
						'type'    => 'radio',
						'default' => 'whaaadasa sad as das'
					),
				)
			),

			'tab4' => array(
				'label'  => '4 tab title',
				'fields' => array(
					'toogler' => array(
						'label'   => 'Checkbox Example',
						'type'    => 'checkbox',
						'default' => 'whaaadasa sad as das'
					),

					'select' => array(
						'label'   => 'Select Example',
						'type'    => 'select',
						'default' => 'whaaadasa sad as das'
					),

					'radio' => array(
						'label'   => 'Example',
						'type'    => 'radio',
						'default' => 'whaaadasa sad as das'
					),
				)
			)
		);

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	// Register a settings page
	function add_admin_menu() {
		$admin_page = add_submenu_page( 'options-general.php', $this->name, $this->name, 'manage_options', 'adminoptions', array(
			$this,
			'adminoptions_options_page'
		) );
	}

	function adminoptions_options_page() {
		$state = $this->get_option( 'state' ); ?>
		<div class="wrap">
			<div class="adminoptions-wrapper">
				<h1 class="title"><?php echo $this->name ?></h1>
				<div id="admin_options_dashboard"></div>
			</div>
		</div>
		<?php
	}

	function settings_init() {
		register_setting( 'adminoptions', 'adminoptions_settings' );

		add_settings_section(
			'adminoptions_section',
			$this->name . esc_html__( ' description', 'adminoptions' ),
			null,
			'adminoptions'
		);
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( $this->is_admin_options_dashboard() ) {
			wp_enqueue_style( 'adminoptions-dashboard', plugin_dir_url( __FILE__ ) . 'css/admin-page.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( $this->is_admin_options_dashboard() ) {

			wp_enqueue_script( 'adminoptions-dashboard', plugin_dir_url( __FILE__ ) . 'js/admin-page.js', array(
				'jquery',
				'wp-util'
			), $this->version, true );

			$this->localize_js_data( 'adminoptions-dashboard' );
		}
	}


	function localize_js_data( $key ) {
		$state = $this->get_option( 'state' );

		$localized_data = array(
			'state'     => $state,
			'wp_rest'   => array(
				'root'               => esc_url_raw( rest_url() ),
				'nonce'              => wp_create_nonce( 'wp_rest' ),
				'adminoptions_nonce' => wp_create_nonce( 'adminoptions_rest' )
			),
			'admin_url' => admin_url(),
			'config'    => $this->config,
			'options'   => $this->options
		);

		wp_localize_script( $key, 'adminoptions', $localized_data );
	}


	function add_rest_routes_api() {
		//The Following registers an api route with multiple parameters.
		register_rest_route( 'adminoptions/v1', '/react_state', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'rest_get_state' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' )
		) );

		register_rest_route( 'adminoptions/v1', '/react_state', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'rest_set_state' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' )
		) );
	}

	function permission_nonce_callback() {
		$nonce = '';

		if ( isset( $_REQUEST['adminoptions_nonce'] ) ) {
			$nonce = $_REQUEST['adminoptions_nonce'];
		} elseif ( isset( $_POST['adminoptions_nonce'] ) ) {
			$nonce = $_POST['adminoptions_nonce'];
		}

		return wp_verify_nonce( $nonce, 'adminoptions_rest' );
	}

	function rest_get_state() {
		$state = $this->get_option( 'state' );
		wp_send_json_success( $state );
	}

	function rest_set_state() {
		if ( empty( $_POST['state'] ) || ! is_array( $_POST['state'] ) ) {
			wp_send_json_error( esc_html__( 'Wrong state param', 'adminoptions' ) );
		}

		$this->options['state'] = $_POST['state'];
		$this->save_options();
		wp_send_json_success( $this->options['state'] );
	}

	/**
	 * Helpers
	 **/
	function is_admin_options_dashboard() {
		if ( ! empty( $_GET['page'] ) && 'adminoptions' === $_GET['page'] ) {
			return true;
		}

		return false;
	}

	function set_options() {
		$this->options = get_option( 'adminoptions_options' );
	}

	function save_options() {
		update_option( 'adminoptions_options', $this->options );
	}

	function get_options() {
		if ( empty( $this->options ) ) {
			$this->set_options();
		}

		return $this->options;
	}

	function get_option( $option, $default = null ) {
		$options = $this->get_options();

		if ( ! empty( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		if ( $default !== null ) {
			return $default;
		}

		return null;
	}
}

$admin_options = new Gridable_Admin_Page( '1.1.0' );