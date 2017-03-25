<?php
/**
 * Plugin Name:       Socket Plugin Example
 * Plugin URI:        https://andrei-lupu.com/
 * Description:       This is a Socket Framework Plugin Example
 * Version:           1.1.0
 * Author:            Pixelgrade
 * Author URI:        https://andrei-lupu.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       socket
 * Domain Path:       /languages
 */


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Socket
 * @subpackage Socket/admin
 * @author     Pixelgrade <contact@pixelgrade.com>
 */

//var_dump( get_option('pixcustomify_settings'));
add_filter( 'socket_config_for_socket', function ( $config ) {

	return array(
		'grid_example' => array(
			'label' => 'Section Name',
			'items' => array(
				'fmulticheckbox'    => array(
					'type'  => 'multicheckbox',
					'label' => 'Multi checks',
					'default' => array(
						'test2' => 'on',
						'test4' => 'on',
					),
					'options' => array(
						'test' => "Label 1",
						'test2' => "Label 2",
						'test3' => "Label 3",
						'test4' => "Label 4",
						'test5' => "Label 5",
					)
				),
				'firt_radio'    => array(
					'type'  => 'radio',
					'label' => 'Radio',
					'default' => 'test2',
					'options' => array(
						'test' => "Label 1",
						'test2' => "Label 2",
						'test3' => "Label 3",
					)
				),
				'name'        => array(
					'type'  => 'text',
					'label' => 'Name',
					'placeholder' => 'Just a text field',

				),
				'email'       => array(
					'type'  => 'text',
					'label' => 'Email'
				),
				'first_toggle'      => array(
					'type'  => 'toggle',
					'label' => 'Just a checkbox',
					'default' => 1
				),
				'anothertext' => array(
					'type'  => 'text',
					'label' => 'Just Another text'
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

	return $config;
} );


class PluginExampleAdminPage {
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

	private $key = 'socket';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
		$this->name    = esc_html__( 'Socket Admin Page', 'socket' );

		$this->config = apply_filters( 'socket_config_for_' . $this->key, array() );

		add_action( 'rest_api_init', array( $this, 'add_rest_routes_api' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$this->set_defaults( $this->config );
	}

	// Register a settings page
	function add_admin_menu() {
		$admin_page = add_submenu_page( 'options-general.php', $this->name, $this->name, 'manage_options', 'socket', array(
			$this,
			'socket_options_page'
		) );
	}

	function socket_options_page() {
		$state = $this->get_option( 'state' ); ?>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.2/semantic.min.css"></link>
		<div class="wrap">
			<div class="socket-wrapper">
				<header class="title">
					<h1 class="page-title"><?php echo $this->name ?></h1>
					<div class="description"><?php echo $this->description ?></div>
				</header>
				<div class="content">
					<div id="socket_dashboard"></div>
				</div>
			</div>
		</div>
		<?php
	}

	function settings_init() {
		register_setting( 'socket', 'socket_settings' );

		add_settings_section(
			'socket_section',
			$this->name . esc_html__( ' My plugin description description', 'socket' ),
			null,
			'socket'
		);
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( $this->is_socket_dashboard() ) {
			wp_enqueue_style(
				'socket-dashboard',
				plugin_dir_url( __FILE__ ) . 'socket/css/socket.css',
				array(),
				filemtime(plugin_dir_path( __FILE__ ) . 'socket/css/socket.css'),
				'all'
			);
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( $this->is_socket_dashboard() ) {
			wp_enqueue_script( 'socket-dashboard', plugin_dir_url( __FILE__ ) . 'socket/js/socket.js', array(
				'jquery',
				'wp-util'
			),
			filemtime(plugin_dir_path( __FILE__ ) . 'socket/js/socket.js'), true );

			$this->localize_js_data( 'socket-dashboard' );
		}
	}


	function localize_js_data( $key ) {
		$values = $this->get_option( 'state' );

		$localized_data = array(
			'wp_rest'   => array(
				'root'               => esc_url_raw( rest_url() ),
				'nonce'              => wp_create_nonce( 'wp_rest' ),
				'socket_nonce' => wp_create_nonce( 'socket_rest' )
			),
			'admin_url' => admin_url(),
			'config'    => $this->config,
			'values'    => $this->values
		);

		wp_localize_script( $key, 'socket', $localized_data );
	}

	function add_rest_routes_api() {
		//The Following registers an api route with multiple parameters.
		register_rest_route( 'socket/v1', '/option', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'rest_get_state' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' )
		) );

		register_rest_route( 'socket/v1', '/option', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'rest_set_state' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' )
		) );

		// debug tools
		register_rest_route( 'socket/v1', '/cleanup', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'rest_cleanup' ),
			'permission_callback' => array( $this, 'permission_nonce_callback' ),
		) );
	}

	function permission_nonce_callback() {
		$nonce = '';

		if ( isset( $_REQUEST['socket_nonce'] ) ) {
			$nonce = $_REQUEST['socket_nonce'];
		} elseif ( isset( $_POST['socket_nonce'] ) ) {
			$nonce = $_POST['socket_nonce'];
		}

		return wp_verify_nonce( $nonce, 'socket_rest' );
	}

	function rest_get_state() {
		$state = $this->get_option( 'state' );
		wp_send_json_success( $state );
	}

	function rest_set_state() {
		if ( empty( $_POST['name'] ) || empty( $_POST['value'] ) ) {
			wp_send_json_error( esc_html__( 'Wrong state params', 'socket' ) );
		}

		$this->get_values();

		$option_name = $_POST['name'];

		$option_value = $_POST['value'];

		$this->values[$option_name] = $option_value;
		wp_send_json_success( $this->save_values() );
	}


	function rest_cleanup() {

		if ( empty( $_POST['test1'] ) || empty( $_POST['test2'] ) || empty( $_POST['confirm'] ) ) {
			wp_send_json_error( 'nah' );
		}

		if ( (int) $_POST['test1'] + (int) $_POST['test2'] === (int) $_POST['confirm'] ) {
			$current_user = _wp_get_current_user();

			$this->values= array();
			wp_send_json_success( $this->save_values() );

			wp_send_json_success( 'ok' );
		}

		wp_send_json_error( array(
			$_POST['test1'],
			$_POST['test2'],
			$_POST['confirm']
		) );
	}

	/**
	 * Helpers
	 **/
	function is_socket_dashboard() {
		if ( ! empty( $_GET['page'] ) && 'socket' === $_GET['page'] ) {
			return true;
		}

		return false;
	}

	function set_values() {
		$this->values = get_option( $this->key );
		if ( $this->values === false ) {
			$this->values = $this->defaults;
		} elseif ( ! empty( $this->defaults ) && count( array_diff_key( $this->defaults, $this->values ) ) != 0 ) {
			$this->values = array_merge( $this->defaults, $this->values );
		}
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

$socket = new PluginExampleAdminPage( '1.1.0' );