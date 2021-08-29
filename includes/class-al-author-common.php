<?php
/*
 * The common functionality of the plugin.
 *
 * @package    Al_Author
 * @subpackage Al_Author/common
 * @author     Adam Luzsi <luzsiadam@gmail.com>
 */
class Al_Author_Common {

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
	 * Register the Auhtors CPT
	 * 
	 * @since    1.0.0
	 */
	public function register_authors_cpt() {

		$labels = array(
			'name' => _x('Authors', 'post type general name'),
			'singular_name' => _x('Author', 'post type singular name'),
			'add_new' => _x('Add New', 'person'),
			'add_new_item' => __('Add New Author'),
			'edit_item' => __('Edit Author'),
			'new_item' => __('New Author'),
			'all_items' => __('All Authors'),
			'view_item' => __('View Author'),
			'search_items' => __('Search Authors'),
			'not_found' =>  __('No author found'),
			'not_found_in_trash' => __('No auhtor found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'Authors'
		);
		$args = array(
			'labels' => $labels,
			'description' => 'A post type for authors.',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'hierarchical' => false,
			'supports' => array('author_info_metabox'),
			'rewrite' => array('slug' => 'authors'),
			'has_archive' => 'authors',
			'menu_icon' => 'dashicons-groups'
		);
		register_post_type('authors',$args);
	}
	
}
