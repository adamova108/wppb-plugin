<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/adamova108
 * @since      1.0.0
 *
 * @package    Al_Author
 * @subpackage Al_Author/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Al_Author
 * @subpackage Al_Author/admin
 * @author     Adam Luzsi <luzsiadam@gmail.com>
 */
class Al_Author_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $current_screen;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/al-author-admin.css', array(), $this->version, 'all' );

		//Add the Select2 CSS file - IF - we are editing an Author CPT post
		if ($current_screen->id == 'edit-authors') {
			wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');
		}
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $current_screen;
		
		//Add the Select2 JavaScript file - IF - we are editing an Author CPT post
		if ($current_screen->id == 'edit-authors') {
			wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'jquery', '4.1.0-rc.0');
		}
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/al-author-admin.js', array( 'jquery' ), $this->version, false );
		
	}
	
}
