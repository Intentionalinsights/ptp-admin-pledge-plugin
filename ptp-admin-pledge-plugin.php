<?php
/**
 * @package  PTPAdminPledgePlugin
 */
/**
* Plugin Name: Pro-Truth Pledge Admin Management Page
* Description: Manages the Pro Truth Pledge form data
* Version: 1.0
* Author: Pro-Truth Pledge developers
* Author URI: protruthpledge.org
*/
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );
if ( !class_exists( 'PTPAdminPledgePlugin' ) ) {
	class PTPAdminPledgePlugin
	{
		public $plugin;
		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}
		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}
		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=ptp_admin_pledge">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}
		public function add_admin_pages() {
			add_menu_page( 'PTP Manage', 'PTP Manage', 'manage_options', 'ptp_admin_pledge', array( $this, 'admin_index' ), 'dashicons-store', 110 );
			add_submenu_page( 'ptp_admin_pledge', 'Edit pledge', 'Edit Pledge', 'manage_options', 'ptp_edit_pledge', array ($this, 'admin_edit'));
		}
		public function admin_index() {
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}
		public function admin_edit() {
			require_once plugin_dir_path( __FILE__ ) . 'templates/edit_pledge.php';
		}
		function enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/mystyle.css', __FILE__ ) );
			wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/myscript.js', __FILE__ ) );
		}
		function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'inc/ptp-admin-pledge-plugin-activate.php';
			PTPAdminPledgePluginActivate::activate();
		}
	}
	$ptpAdminPledgePlugin = new PTPAdminPledgePlugin();
	$ptpAdminPledgePlugin->register();
	// activation
	register_activation_hook( __FILE__, array( $ptpAdminPledgePlugin, 'activate' ) );
	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/ptp-admin-pledge-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'PTPAdminPledgePluginDeactivate', 'deactivate' ) );
}