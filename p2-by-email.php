<?php
/*
Plugin Name: P2 By Email
Version: 0.1-alpha
Description: For those who like to interact with P2 by email.
Author: danielbachhuber
Author URI: http://danielbachhuber.com/
Plugin URI: PLUGIN SITE HERE
Text Domain: p2-by-email
Domain Path: /languages
*/

/**
 * @todo:
 * - Send a HTML-ified email notification on new posts and comments
 * - Allow emails to be sent from a Gmail account, and replied to directly
 * - Create a new post by email
 * - @mentions force an email to be sent to a user, if the user exists. Otherwise, bold the user's login
 */

class P2_By_Email {

	private $data;

	private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new P2_By_Email;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	private function __construct() {
		/** Prevent the class from being loaded more than once **/
	}

	public function __isset( $key ) {
		return isset( $this->data[$key] );
	}

	public function __get( $key ) {
		return isset( $this->data[$key] ) ? $this->data[$key] : null;
	}

	public function __set( $key, $value ) {
		$this->data[$key] = $value;
	}

	private function setup_globals() {

		$this->file           = __FILE__;
		$this->basename       = apply_filters( 'p2be_plugin_basenname', plugin_basename( $this->file ) );
		$this->plugin_dir     = apply_filters( 'p2be_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url     = apply_filters( 'p2be_plugin_dir_url',   plugin_dir_url ( $this->file ) );

		$this->extend         = new stdClass();

	}

	private function includes() {

		require_once( $this->plugin_dir . 'inc/class-p2be-emails.php' );
		require_once( $this->plugin_dir . 'inc/class-p2be-email-replies.php' );

		if ( defined('WP_CLI') && WP_CLI )
			require_once( $this->plugin_dir . 'inc/class-p2be-wp-cli.php' );
	}

	private function setup_actions() {

		do_action_ref_array( 'p2be_after_setup_actions', array( &$this ) );
	}

	protected function get_following_post( $post_id ) {

		return wp_list_pluck( get_users(), 'user_login' );
	}

	protected function get_template( $template, $vars = array() ) {

		$template_path = dirname( __FILE__ ) . '/templates/' . $template . '.php';

		ob_start();
		if ( file_exists( $template_path ) ) {
			extract( $vars );
			include $template_path;
		}

		return wpautop( ob_get_clean() );
	}

}

function P2_By_Email() {
	return P2_By_Email::get_instance();
}
add_action( 'plugins_loaded', 'P2_By_Email' );