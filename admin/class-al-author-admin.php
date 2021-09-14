<?php

/**
 * The admin-specific functionality of the plugin.
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
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version The version of this plugin.
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

		/**
		 * Enqueue the general admin CSS | instead of the plugin version I use microtime() to prevent browser caching
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/al-author-admin.css', array(), microtime(), 'all' );

		/**
		 * Add the Select2 CSS file - IF - we are editing an Author CPT post
		 */

		if ( 'edit-authors' === $current_screen->id ) {
			wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.13' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $current_screen;

		/**
		 * Add the admin scripts ONLY if we are on the EDIT-AUTHORS page
		 */

		 if ( 'authors' === $current_screen->id ) {
			
			wp_enqueue_script( 'select2-js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', 'jquery', '4.0.13' );
			
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/al-author-admin.js', array( 'jquery' ), microtime(), false );
	
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_media();
		}

	}


	public function create_authors_metabox() {

		add_meta_box(
			'author_info_metabox',
			'Author Information',
			array( $this, 'authors_metabox_html' ),
			'authors'
		);

	}

	public function authors_metabox_save( int $post_id ) {

		$fname = '';
		$lname = '';

		foreach ( $_POST as $pK => $pV ) {
			if ( strpos( $pK, 'al_author_' ) === 0 ) {

				if ( strstr( $pK, 'first_name' ) ) {
					$fname = trim( $pV );
				}

				if ( strstr( $pK, 'last_name' ) ) {
					$lname = trim( $pV );
				}

				if ( ! is_array( $pV ) ) {
					$pV = trim( $pV );
				}

				update_post_meta( $post_id, $pK, $pV );
			}
		}

		if ( ! empty( $fname ) && ! empty( $lname ) ) {
			global $wpdb;

			$wpdb->update(
				$wpdb->posts,
				array(
					'post_title' => "$fname $lname",
					'post_name' => sanitize_title( "$fname $lname" ),
				),
				array( 'ID' => $post_id )
			);
		}

	}

	public static function get_author_custom( int $post_id ) {

		global $wpdb;

		$al_author_custom = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE 'al_author_%'" );

		$author_data = array();

		if ( $al_author_custom ) {
			foreach ( $al_author_custom as $aC ) {
				$author_data[ $aC->meta_key ] = maybe_unserialize( $aC->meta_value );
			}
		}

		return $author_data;
	}


	private function get_user_option_list( $selected ) {

		global $wpdb;

		$users = $wpdb->get_results( "SELECT ID, display_name FROM $wpdb->users" );

		$user_option_list = "<option value='-1'>[ Select User ]</option>";

		if ( $users ) {
			foreach ( $users as $u ) {
				$user_option_list .= '<option ' . selected( $selected, $u->ID, false ) . " value='{$u->ID}'>{$u->display_name}</option>";
			}
		}

		return $user_option_list;
	}


	public function authors_metabox_html( $post ) {

		$author_data = $this->get_author_custom( $post->ID );

		$al_author_link_user = isset( $author_data['al_author_link_user'] ) && is_numeric( $author_data['al_author_link_user'] ) ? $author_data['al_author_link_user'] : -1;

		$al_author_gallery = isset( $author_data['al_author_gallery'] ) ? $author_data['al_author_gallery'] : array();

		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="al_author_first_name">First name</label></th>
					<td><input required class="widefat" type="text" name="al_author_first_name" id="al_author_first_name" value="<?php echo isset( $author_data['al_author_first_name'] ) ? $author_data['al_author_first_name'] : ''; ?>" /></td>
				</tr>
				<tr>
					<th><label for="al_author_last_name">Last name</label></th>
					<td><input required class="widefat" type="text" name="al_author_last_name" id="al_author_last_name" value="<?php echo isset( $author_data['al_author_last_name'] ) ? $author_data['al_author_last_name'] : ''; ?>" /></td>
				</tr>
				<tr>
					<th><label for="al_author_profile_pic">Profile image</label></th>
					<td>
						<input type="hidden" id="al_author_profile_pic" name="al_author_profile_pic" value="<?php echo isset( $author_data['al_author_profile_pic'] ) ? $author_data['al_author_profile_pic'] : ''; ?>">
						<div id="al_author_profile_pic_wrapper">
							<?php
							if ( isset( $author_data['al_author_profile_pic'] )
								&& is_numeric( $author_data['al_author_profile_pic'] ) ) {

								echo wp_get_attachment_image( $author_data['al_author_profile_pic'], 'thumbnail', false, array( 'class' => 'al_author_profile_pic_thumbnail' ) );

								echo '<p><input type="button" class="button-secondary button-large button" id="al_author_profile_pic_remove" name="al_author_profile_pic_remove" value="Remove Image" /></p>';
							} else {

								echo '<p><input type="button" class="button-primary button-large button" id="al_author_profile_pic_add" name="al_author_profile_pic_add" value="Add Image" /></p>';
							}
							?>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="al_author_bio">Biography</label></th>
					<td>
						<?php
							$content = $author_data['al_author_bio'] ?? '';
							$editor_id = 'al_author_bio';
							$settings = array(
								'editor_height' => 200,
								'media_buttons' => false,
								//'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ),
								'teeny' => true,
							);
							wp_editor( $content, $editor_id, $settings );
							?>
					</td>
				</tr>
				<tr>
					<th><label for="al_author_fb_url">Facebook URL</label></th>
					<td><input class="widefat" type="url" name="al_author_fb_url" id="al_author_fb_url" value="<?php echo isset( $author_data['al_author_fb_url'] ) ? $author_data['al_author_fb_url'] : ''; ?>" /></td>
				</tr>
				<tr>
					<th><label for="al_author_li_url">LinkedIn URL</label></th>
					<td><input class="widefat" type="url" name="al_author_li_url" id="al_author_li_url" value="<?php echo isset( $author_data['al_author_li_url'] ) ? $author_data['al_author_li_url'] : ''; ?>" /></td>
				</tr>
				<tr>
					<th><label for="al_author_link_user">Link to WP User <em>(optional)</em></label></th>
					<td>
						<select name="al_author_link_user" id="al_author_link_user">
							<?php echo $this->get_user_option_list( $al_author_link_user ); ?>
						</select>
					</td>
				</tr>
				
				<tr>
					<th><label>Photo gallery</label></th>
					<td>
					<div id="al_author_gallery_current">
					<?php

					$additionalImagesCount = count( $al_author_gallery ) + 1;

					if ( ! empty( $al_author_gallery ) ) :

						$additionalImagesCount = 1;

						foreach ( $al_author_gallery as $image_ID ) {

							$image_ID = intval( $image_ID );

							$image_URL = wp_get_attachment_image_src( $image_ID, 'thumbnail' );
							$image_URL = array_shift( $image_URL );
							?>
								
							<div class="al_author_gallery_image_wrapper" id="al_author_gallery_new<?php echo $additionalImagesCount; ?>">
								<div class="al_author_gallery_image" id="additionalImageThumb<?php echo $additionalImagesCount; ?>">
									<img title="Hold mouse button to drag and sort" src="<?php echo $image_URL; ?>" />
								</div>
								<input type="hidden" name="al_author_gallery[]" id="additional_image_id_<?php echo $additionalImagesCount; ?>" value="<?php echo $image_ID; ?>"/>
								<a href="#" title="Change image" class="additional-image-upload al-button dashicons-edit-large dashicons-before" data-additional-id="<?php echo $additionalImagesCount; ?>"></a> 
								<a href="#" title="Remove image" class="additional-image-remove al-button dashicons-no dashicons-before" data-additional-id="<?php echo $additionalImagesCount; ?>"></a>
							</div>
		
							<?php
							$additionalImagesCount++;
						}
						?>
					
					<?php endif; ?>
					</div>
					<script>additionalImages = <?php echo $additionalImagesCount; ?></script>
					<div id="al_author_gallery_news"></div>
					<a class="button button-secondary" href="javascript:;" onclick="al_add_new_image(); return false;"><?php _e( 'Add another image', $this->plugin_name ); ?></a>
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			
		</script>

		<?php
	}


	public function al_author_link_user_ajax_callback() {

		global $wpdb;

		$s = esc_sql( $_REQUEST['s'] );

		$sSQL = $s != '' ? "AND (display_name LIKE '%$s%' OR user_email LIKE '%$s%')" : '';

		$sql = "SELECT ID, display_name, user_email FROM $wpdb->users WHERE 1=1 $sSQL LIMIT 25";

		$results = $wpdb->get_results( $sql );

		if ( $results ) {

			$selectResults = array();

			foreach ( $results as $r ) {

				$html = '<div>';
				$html .= '<strong>' . $r->display_name . '</strong>';
				$html .= '<br/><em>' . $r->user_email . '</em>';
				$html .= '</div>';

				$selectResults[] = array(
					'id' => $r->ID,
					'text' => $r->display_name,
					'html' => $html,
				);
			}

			wp_send_json_success( $selectResults );
		}

		wp_send_json_success();

	}


	public function author_admin_columns( $columns ) {
		$columns_clone = $columns;
		$columns = array();
		$columns['cb'] = $columns_clone['cb'];
		$columns['title'] = $columns_clone['title'];
		$columns['profile_pic'] = __( 'Profile Picture', $this->plugin_name );
		$columns['first_name'] = __( 'First Name', $this->plugin_name );
		$columns['last_name'] = __( 'Last Name', $this->plugin_name );
		$columns['date'] = $columns_clone['date'];
		return $columns;

	}


	function author_admin_sortable_columns( $columns ) {
		$columns['first_name'] = 'first_name';
		$columns['last_name'] = 'last_name';
		return $columns;
	}



	public function author_admin_columns_switch( $column, $post_id ) {

		switch ( $column ) {

			case 'profile_pic':
				$profile_pic_id = get_post_meta( $post_id, 'al_author_profile_pic', true );
				if ( is_numeric( $profile_pic_id ) ) {
					$image_URL = wp_get_attachment_image_src( $profile_pic_id, 'thumbnail' );
					$image_URL = array_shift( $image_URL );
					echo "<img width='80' src='$image_URL' />";
				} else {
					echo '<em>No image.</em>';
				}

				break;

			case 'first_name':
				echo get_post_meta( $post_id, 'al_author_first_name', true );
				break;

			case 'last_name':
				echo get_post_meta( $post_id, 'al_author_last_name', true );
				break;
		}
	}



	public function authors_archive_query_override( $query ) {
		if ( is_post_type_archive( 'authors' ) ) {
			$query->set( 'order', 'ASC' );
			$query->set( 'orderby', 'title' );
		}
	}

}
