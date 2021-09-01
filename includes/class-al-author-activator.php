<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/adamova108
 * @since      1.0.0
 *
 * @package    Al_Author
 * @subpackage Al_Author/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Al_Author
 * @subpackage Al_Author/includes
 * @author     Adam Luzsi <luzsiadam@gmail.com>
 */
class Al_Author_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Flush the rewrite rules upon activation
		flush_rewrite_rules( true );

	}

}
