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

add_filter( 'adminoptions_config', function ( $config ) {

	return array(

		'grid_example' => array(
			'label' => 'Section Name',
			'items' => array(
				'first_checkbox'    => array(
					'type'  => 'checkbox',
					'label' => 'The cjeck',
					'default' => 0
				),
				'name'        => array(
					'type'  => 'text',
					'label' => 'Name',
					'default' => 'ok oko k'
				),
				'email'       => array(
					'type'  => 'text',
					'label' => 'Email'
				),
				'first_toggle'      => array(
					'type'  => 'toggle',
					'label' => 'The ctogglejeck',
					'default' => 1
				),
				'anothertext' => array(
					'type'  => 'text',
					'label' => 'Another text'
				),
			)
		),

		'grid_example2' => array(
			'label' => 'Second Section',
			'items' => array(
				'whattext'   => array(
					'type'  => 'text',
					'label' => 'The text'
				),
				'le_toggle' => array(
					'type'  => 'toggle',
					'label' => 'The ctogglejeck'
				),
			)
		),


		'grid_example3' => array(
			'label' => 'Section Name 3',
			'items' => array(
				'acheckbox' => array(
					'type'  => 'checkbox',
					'label' => 'The cjeck'
				),
				'atoggle'   => array(
					'type'  => 'toggle',
					'label' => 'The ctogglejeck'
				),
			)
		),

		'grid_example4' => array(
			'label' => ' 4 Section',
			'items' => array(
				'dasdastext'   => array(
					'type'  => 'text',
					'label' => 'The text'
				),
				'dasdasdasoggle' => array(
					'type'  => 'toggle',
					'label' => 'The ctogglejeck'
				),
			)
		),
	);

	$config = array(
		'tab1' => array(
			'label'  => 'Tab title',
			'fields' => array(
				'dasdastexter' => array(
					'label'   => 'Example',
					'type'    => 'text',
					'default' => 'whaaadasa sad as das'
				),

				'dasdasdatextera' => array(
					'label'   => 'Checkbos Example',
					'type'    => 'checkbox',
					'default' => 'whaaadasa sad as das'
				),

				'dasdasdascheckb' => array(
					'label'   => 'toggle Example',
					'type'    => 'toggle',
					'default' => 'whaa aaaa sssss s'
				),

				'aaaddaeditor' => array(
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

				'aselect' => array(
					'label'   => 'Select Example',
					'type'    => 'select',
					'default' => 'whaaadasa sad as das'
				),

				'aradio' => array(
					'label'   => 'Example',
					'type'    => 'radio',
					'default' => 'whaaadasa sad as das'
				),
			)
		),

		'tab3' => array(
			'label'  => '3 tab title',
			'fields' => array(
				'wattoogler' => array(
					'label'   => 'Checkbox Example',
					'type'    => 'checkbox',
					'default' => 'whaaadasa sad as das'
				),

				'watselect' => array(
					'label'   => 'Select Example',
					'type'    => 'select',
					'default' => 'whaaadasa sad as das'
				),

				'watradio' => array(
					'label'   => 'Example',
					'type'    => 'radio',
					'default' => 'whaaadasa sad as das'
				),
			)
		),

		'tab4' => array(
			'label'  => '4 tab title',
			'fields' => array(
				'qweqgastoogler' => array(
					'label'   => 'Checkbox Example',
					'type'    => 'checkbox',
					'default' => 'whaaadasa sad as das'
				),

				'dasdselect' => array(
					'label'   => 'Select Example',
					'type'    => 'select',
					'default' => 'whaaadasa sad as das'
				),

				'asradio' => array(
					'label'   => 'Example',
					'type'    => 'radio',
					'default' => 'whaaadasa sad as das'
				),
			)
		)
	);

	return $config;
} );


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

	private $description;

	private $values;

	private $config;

	private $defaults;

	private $key = 'adminoptions';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
		$this->name    = esc_html__( 'Options Page Name', 'adminoptions' );

		$this->config = apply_filters( 'adminoptions_config', array() );

		add_action( 'rest_api_init', array( $this, 'add_rest_routes_api' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$this->set_defaults( $this->config );
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
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.2/semantic.min.css"></link>
		<div class="wrap">
			<div class="adminoptions-wrapper">
				<header class="title">
					<h1 class="page-title"><?php echo $this->name ?></h1>
					<div class="description"><?php echo $this->description ?></div>
				</header>

				<div class="content">
					<div id="admin_options_dashboard"></div>
				</div>
				<footer>

				</footer>
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
		$values = $this->get_option( 'state' );

		$localized_data = array(
			'wp_rest'   => array(
				'root'               => esc_url_raw( rest_url() ),
				'nonce'              => wp_create_nonce( 'wp_rest' ),
				'adminoptions_nonce' => wp_create_nonce( 'adminoptions_rest' )
			),
			'admin_url' => admin_url(),
			'config'    => $this->config,
			'values'    => $this->values
		);

		wp_localize_script( $key, 'adminoptions', $localized_data );
	}

	function add_rest_routes_api() {
		//The Following registers an api route with multiple parameters.
		register_rest_route( 'adminoptions/v1', '/option', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'rest_get_state' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' )
		) );

		register_rest_route( 'adminoptions/v1', '/option', array(
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
		if ( empty( $_POST['name'] ) || empty( $_POST['value'] ) ) {
			wp_send_json_error( esc_html__( 'Wrong state params', 'adminoptions' ) );
		}

		$this->get_values();

		$option_name = $_POST['name'];

		$option_value = $_POST['value'];

		$this->values[$option_name] = $option_value;
		wp_send_json_success( $this->save_values() );
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

	function set_values() {
		$this->values = get_option( $this->key);

		if ( $this->values === false ) {
			$this->values = $this->defaults;
		} else if ( count( array_diff_key( $this->defaults, $this->values ) ) != 0 ) {
			$this->values = array_merge( $this->defaults, $this->values );
		}
//		var_dump($this->values);
	}

	function save_values() {
		return update_option( $this->key, $this->values );
	}

	function set_defaults( $array ) {

		if ( ! empty( $array ) ) {

			foreach ( $array as $key => $value ) {

				if ( ! is_array( $value ) ) {
					continue;
				}

				$result = array_key_exists('default', $value);

				if ( $result ) {
					$this->defaults[$key] = $value['default'];
				} elseif ( is_array( $value ) ) {
					$this->set_defaults( $value );
				}
			}
		}
	}

	function get_values() {
		if ( empty( $this->values ) ) {
			$this->set_values();
		}

		return $this->values;
	}

	function get_option( $option, $default = null ) {
		$values = $this->get_values();

		if ( ! empty( $values[ $option ] ) ) {
			return $values[ $option ];
		}

		if ( $default !== null ) {
			return $default;
		}

		return null;
	}


	function array_key_exists_r($needle, $haystack) {
		$result = array_key_exists($needle, $haystack);

		if ($result) {
			return $result;
		}

		foreach ($haystack as $v) {
			if (is_array($v)) {
				$result = array_key_exists_r($needle, $v);
			}
			if ($result) {
				return $result;
			}
		}

		return $result;
	}
}

$admin_options = new Gridable_Admin_Page( '1.1.0' );