<?php
/**
 * Plugin class
 **/
if ( ! class_exists( 'Wp_Travel_Engine_Taxonomy_Thumb' ) ) {

	class Wp_Travel_Engine_Taxonomy_Thumb {

		public function __construct() {
		}

		/*
		* Initialize the class and start calling our hooks and filters
		* @since 1.0.0
		*/
		public function init() {
			add_action( 'destination_add_form_fields', array( $this, 'wpte_add_category_image' ), 10, 2 );
			add_action( 'created_destination', array( $this, 'wpte_save_category_image' ), 10, 2 );
			add_action( 'destination_edit_form_fields', array( $this, 'wpte_update_category_image' ), 10, 2 );
			add_action( 'edited_destination', array( $this, 'wpte_updated_category_image' ), 10, 2 );

			add_action( 'activities_add_form_fields', array( $this, 'wpte_add_category_image' ), 10, 2 );
			add_action( 'created_activities', array( $this, 'wpte_save_category_image' ), 10, 2 );
			add_action( 'activities_edit_form_fields', array( $this, 'wpte_update_category_image' ), 10, 2 );
			add_action( 'edited_activities', array( $this, 'wpte_updated_category_image' ), 10, 2 );

			add_action( 'trip_types_add_form_fields', array( $this, 'wpte_add_category_image' ), 10, 2 );
			add_action( 'created_trip_types', array( $this, 'wpte_save_category_image' ), 10, 2 );
			add_action( 'trip_types_edit_form_fields', array( $this, 'wpte_update_category_image' ), 10, 2 );
			add_action( 'edited_trip_types', array( $this, 'wpte_updated_category_image' ), 10, 2 );

			add_action( 'difficulty_add_form_fields', array( $this, 'wpte_add_category_image' ), 10, 2 );
			add_action( 'created_difficulty', array( $this, 'wpte_save_category_image' ), 10, 2 );
			add_action( 'difficulty_edit_form_fields', array( $this, 'wpte_update_category_image' ), 10, 2 );
			add_action( 'edited_difficulty', array( $this, 'wpte_updated_category_image' ), 10, 2 );

			add_action( 'admin_enqueue_scripts', array( $this, 'wpte_load_media' ) );
			add_action( 'admin_footer', array( $this, 'wpte_add_script' ) );

			/* Apply `the_content` filters to term description */
			if ( isset( $GLOBALS['wp_embed'] ) ) {
				add_filter( 'term_description', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
				add_filter( 'term_description', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
			}

			add_filter( 'term_description', 'wptexturize' );
			add_filter( 'term_description', 'convert_smilies' );
			add_filter( 'term_description', 'convert_chars' );
			add_filter( 'term_description', 'wpautop' );

			if ( ! is_admin() ) {
				add_filter( 'term_description', 'shortcode_unautop' );
				add_filter( 'term_description', 'do_shortcode', 11 );
			}

			add_action( 'destination_edit_form_fields', array( $this, 'add_editor_field_description' ), 1, 2 );
			add_action( 'activities_edit_form_fields', array( $this, 'add_editor_field_description' ), 1, 2 );
			add_action( 'trip_types_edit_form_fields', array( $this, 'add_editor_field_description' ), 1, 2 );
			add_action( 'difficulty_edit_form_fields', array( $this, 'add_editor_field_description' ), 1, 2 );

			add_action( 'destination_add_form_fields', array( $this, 'add_shortdesc_in_wte' ) );
			add_action( 'activities_add_form_fields', array( $this, 'add_shortdesc_in_wte' ) );
			add_action( 'trip_types_add_form_fields', array( $this, 'add_shortdesc_in_wte' ) );

			add_action( 'created_destination', array( $this, 'save_shortdesc_in_wte' ) );
			add_action( 'created_activities', array( $this, 'save_shortdesc_in_wte' ) );
			add_action( 'created_trip_types', array( $this, 'save_shortdesc_in_wte' ) );
			add_action( 'created_difficulty', array( $this, 'save_shortdesc_in_wte' ) );

			add_action( 'destination_edit_form_fields', array( $this, 'update_shortdesc_in_wte' ) );
			add_action( 'activities_edit_form_fields', array( $this, 'update_shortdesc_in_wte' ) );
			add_action( 'trip_types_edit_form_fields', array( $this, 'update_shortdesc_in_wte' ) );

			add_action( 'edited_destination', array( $this, 'updated_shortdesc_in_wte' ) );
			add_action( 'edited_activities', array( $this, 'updated_shortdesc_in_wte' ) );
			add_action( 'edited_trip_types', array( $this, 'updated_shortdesc_in_wte' ) );

		}

		public function wpte_load_media() {
			wp_enqueue_media();
		}

		/*
		* Add a form field in the new category page
		* @since 1.0.0
		*/
		public function wpte_add_category_image( $taxonomy ) {
			?>
			<div class="form-field term-group">
				<?php if ( $taxonomy == 'difficulty' ) {?>
					<label for="category-image-id"><?php esc_html_e( 'Icon', 'wp-travel-engine' ); ?></label>
					<input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
					<div id="category-image-wrapper"></div>
					<p>
						<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e( 'Add Icon', 'wp-travel-engine' ); ?>" />
						<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e( 'Remove Icon', 'wp-travel-engine' ); ?>" />
					</p>
				<?php }
				else {?>
				<label for="category-image-id"><?php esc_html_e( 'Image', 'wp-travel-engine' ); ?></label>
				<input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
				<div id="category-image-wrapper"></div>
				<p>
					<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e( 'Add Image', 'wp-travel-engine' ); ?>" />
					<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'wp-travel-engine' ); ?>" />
				</p>
				<?php }?>
			</div>
			<?php
		}
		/**
		 * Add description editor.
		 */
		public function add_editor_field_description( $tag, $taxonomy ) {
			$settings = array(
				'textarea_name' => 'description',
				'textarea_rows' => 10,
				'editor_class'  => 'i18n-multilingual',
			);

			?>
			<tr class="form-field term-description-wrap">
				<th scope="row"><label for="description"><?php esc_html_e( 'Description', 'wp-travel-engine' ); ?></label></th>
				<td>
					<?php wp_editor( htmlspecialchars_decode( $tag->description ), 'html-tag-description', $settings ); ?>
					<div id="post-status-info">
						<div id="description-word-count" class="hide-if-no-js" style="padding: 5px 10px;">
							<?php
							printf(
								esc_html__( 'Word count: %s', 'wp-travel-engine' ),
								'<span class="word-count">0</span>'
							);
							?>
						</div>
					</div>
					<p class="description"><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'wp-travel-engine' ); ?></p>
				</td>
				<script>
					// Remove the non-html field
					jQuery('textarea#description').closest('.form-field').remove();
				</script>
			</tr>
			<?php
		}

		/**
		 * Add shortdesc_field
		 *
		 * @return void
		 */
		function add_shortdesc_in_wte() {
			?>
			<div class="form-field term-group">
				<label for="wte-shortdesc-textarea"><?php esc_html_e( 'Short Description', 'wp-travel-engine' ); ?></label>
				<textarea name="wte-shortdesc-textarea" id="wte-shortdesc-textarea"></textarea>
			</div>
			<?php
		}

		/**
		 * Save shortdesc field.
		 *
		 * @param [type] $term_id
		 * @return void
		 */
		function save_shortdesc_in_wte( $term_id ) {
			// phpcs:disable
			if ( isset( $_POST['wte-shortdesc-textarea'] ) && '' !== $_POST['wte-shortdesc-textarea'] ) {
				// save our custom fields as wp-options
				add_term_meta( $term_id, 'wte-shortdesc-textarea', sanitize_textarea_field( wp_unslash( $_POST['wte-shortdesc-textarea'] ) ), false );
			}
			// phpcs:enable
		}

		/**
		 * Update shortdesc values.
		 *
		 * @param [type] $term
		 * @return void
		 */
		function update_shortdesc_in_wte( $term ) {
			// collect the term slug
			$term_id = $term->term_id;
			// collect our saved term field information
			$term_category_textarea = get_term_meta( $term_id, 'wte-shortdesc-textarea', true );
			?>
	<tr class="form-field">
		<th valign="top" scope="row">
			<label for="wte-shortdesc-textarea"> <?php esc_html_e( 'Short Description', 'wp-travel-engine' ); ?> </label>
		</th>
		<td>
			<textarea name="wte-shortdesc-textarea" id="wte-shortdesc-textarea"><?php echo wp_kses_post( $term_category_textarea ); ?></textarea>
			<p class="description"><?php esc_html_e( 'This short description is used in taxonomy listing pages', 'wp-travel-engine' ); ?></p>
		</td>
	</tr>
			<?php
		}

		/**
		 * Updated shortdesc values.
		 *
		 * @param [type] $term_id
		 * @return void
		 */
		function updated_shortdesc_in_wte( $term_id ) {
			// phpcs:disable
			if ( isset( $_POST['wte-shortdesc-textarea'] ) && '' !== $_POST['wte-shortdesc-textarea'] ) {
				update_term_meta( $term_id, 'wte-shortdesc-textarea', wp_kses_post( wp_unslash( $_POST['wte-shortdesc-textarea'] ) ) );
			} else {
				update_term_meta( $term_id, 'wte-shortdesc-textarea', '' );
			}
			// phpcs:enable
		}

		/*
		* Save the form field
		* @since 1.0.0
		*/
		public function wpte_save_category_image( $term_id, $tt_id ) {
			// phpcs:disable
			if ( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ) {
				$image = wte_clean( wp_unslash( $_POST['category-image-id'] ) );
				add_term_meta( $term_id, 'category-image-id', (int) $image, true );
			}
			// phpcs:enable
		}

		/*
		* Edit the form field
		* @since 1.0.0
		*/
		public function wpte_update_category_image( $term, $taxonomy ) {
			?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
				<?php if ( $taxonomy == 'difficulty' ) {?>
					<label for="category-image-id"><?php esc_html_e( 'Icon', 'wp-travel-engine' ); ?></label>
				<?php } else{ ?>
					<label for="category-image-id"><?php esc_html_e( 'Image', 'wp-travel-engine' ); ?></label>
					<?php 
				}?>
				</th>
				<td>
					<?php $image_id = get_term_meta( $term->term_id, 'category-image-id', true ); ?>
					<input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo esc_attr( $image_id ); ?>">
					<div id="category-image-wrapper">
						<?php
						if ( $image_id ) {
							echo wp_get_attachment_image( $image_id, 'thumbnail' );  
						}
						?>
					</div>
					<p>
					<?php if ( $taxonomy == 'difficulty' ) {?>
						<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e( 'Add Icon', 'wp-travel-engine' ); ?>" />
						<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e( 'Remove Icon', 'wp-travel-engine' ); ?>" />
					<?php } else { ?>
						<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e( 'Add Image', 'wp-travel-engine' ); ?>" />
						<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'wp-travel-engine' ); ?>" />
						<?php }?>
					</p>
				</td>
			</tr>
			<?php
		}

		/*
		* Update the form field value
		* @since 1.0.0
		*/
		public function wpte_updated_category_image( $term_id, $tt_id ) {
			// phpcs:disable
			if ( ! empty( $_POST['category-image-id'] ) ) {
				$image = wte_clean( wp_unslash( $_POST['category-image-id'] ) );
				update_term_meta( $term_id, 'category-image-id', (int) $image );
			} else {
				update_term_meta( $term_id, 'category-image-id', '' );
			}
			// phpcs:enable
		}

		/*
		* Add script
		* @since 1.0.0
		*/
		public function wpte_add_script() {
			$screen = get_current_screen();

			if ( 'destination' === $screen->taxonomy || 'activities' === $screen->taxonomy
			|| 'trip_types' === $screen->taxonomy || 'difficulty' === $screen->taxonomy ) {
				?>
				<script>
				jQuery(function($) {
					var mediaUploader;
						$('.ct_tax_media_button.button').on('click', function(e) {
							e.preventDefault();
							if (mediaUploader) {
								mediaUploader.open();
								return;
							}
							mediaUploader = wp.media.frames.file_frame = wp.media({
								title: 'Choose Image',
								library: {
									type: 'image',
								},
								button: {
								text: 'Choose Image'
							}, multiple: false });


							mediaUploader.on('select', function() {
								var attachment = mediaUploader.state().get('selection').first().toJSON();
								$('#image-url').val(attachment.url);
								$('#category-image-id').val(attachment.id);
								$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
								$('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
								var selection = mediaUploader.state().get('selection');
								var selected = '';// the id of the image
								// if (selected) {
								selection.add(wp.media.attachment(selected));
								if (typeof uploadSuccess !== 'undefined') {
								// First backup the function into a new variable.
										var uploadSuccess_original = uploadSuccess;
										// The original uploadSuccess function with has two arguments: fileObj, serverData
										// So we globally declare and override the function with two arguments (argument names shouldn't matter)
										uploadSuccess = function(fileObj, serverData)
										{
												// Fire the original procedure with the same arguments
												uploadSuccess_original(fileObj, serverData);
												// Execute whatever you want here:
												alert('Upload Complete!');
										}
								}

								// Hack for "Insert Media" Dialog (new plupload uploader)

								// Hooking on the uploader queue (on reset):
								if (typeof wp.Uploader !== 'undefined' && typeof wp.Uploader.queue !== 'undefined') {
										wp.Uploader.queue.on('reset', function() {
												alert('Upload Complete!');
										});
								}
							});
								// Open the uploader dialog
								mediaUploader.open();
						});

						$('body').on('click','.ct_tax_media_remove',function(){
							$('#category-image-id').val('');
							$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
						});

						// Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
						$(document).ajaxComplete(function(event, xhr, settings) {
							var queryStringArr = settings.data.split('&');
							if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
								var xml = xhr.responseXML;
								$response = $(xml).find('term_id').text();
								if($response!=""){
									// Clear the thumb image
									$('#category-image-wrapper').html('');
								}
							}
						});
					});
				</script>
				<?php
			}
		}

	}
	$wte_taxonomy_thumb = new \Wp_Travel_Engine_Taxonomy_Thumb();
	$wte_taxonomy_thumb->init();

}

