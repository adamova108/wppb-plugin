<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Al_Author
 * @subpackage Al_Author/public
 * @author     Adam Luzsi <luzsiadam@gmail.com>
 */
class Al_Author_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/al-author-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/al-author-public.js', array( 'jquery' ), $this->version, false );

	}


	public function template_loader( $template ) {
		
		if (is_singular('authors')) {
			
			if (file_exists(get_stylesheet_directory().'/single-authors.php')) {
				$template = get_stylesheet_directory().'/single-authors.php';
			}
			else if (file_exists(dirname( __FILE__, 2 ) . '/templates/single-authors.php')){
				$template = dirname( __FILE__, 2 ) . '/templates/single-authors.php';
			}

		}
    
		return $template;
	}

}
