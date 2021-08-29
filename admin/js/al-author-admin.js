(function( $ ) {
	'use strict';

	/**
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 */
	
	
	$(function() {
		
		function al_add_new_image() {
		
			let imageHtml = "";
			
			imageHtml += '<div class="al_author_gallery_image_wrapper" id="al_author_gallery_new'+additionalImages+'">';
			imageHtml += '<div class="al_author_gallery_image" id="additionalImageThumb'+additionalImages+'"></div>';
			imageHtml += '<input type="hidden" name="al_author_gallery[]" id="additional_image_id_'+additionalImages+'" value=""/>';
			imageHtml += '<a href="#" title="Change image" class="additional-image-upload al-button dashicons-edit-large dashicons-before" data-additional-id="'+additionalImages+'"></a>';
			imageHtml += '<a href="#" title="Remove image" class="additional-image-remove al-button dashicons-no dashicons-before" data-additional-id="'+additionalImages+'"></a>';
			imageHtml += '</div><br/>';
		
			jQuery('#al_author_gallery_current').append(imageHtml);
			jQuery('#al_author_gallery_current' + jQuery('#al_author_gallery_current .al_author_gallery_image_wrapper').length).fadeIn();
			jQuery('#al_author_gallery_new'+additionalImages+' .additional-image-upload').click();
			additionalImages++;
		}
		// Check if field is empty
		$(document).on('click', '#publish', function(e){ 
			
			if ($.trim($('#al_author_first_name').val()).length < 1 || $.trim($('#al_author_last_name').val()).length < 1) {
				e.preventDefault();
				alert('Make sure to fill in both First Name and Last Name fields.');
			}

		});



		// Select2 Init

		if (typeof $.fn.select2 === 'function') {
			
			$("#al_author_link_user").select2({
				ajax: { 
					url: ajaxurl,
					dataType: "json",
					delay: 250, 
					data: function (params) {
						return {
							s: params.term, 
							action: "al_author_link_user_ajax"
						};
					},
					processResults: function (response) {
						return {
							results: $.map(response.data, function (obj) {
								return {
									id: obj.id,
									text: obj.text,
									html: obj.html,
								};
							})
						};
					},
					minimumInputLength: 1,
					cache: true,
				},
				dropdownAutoWidth : true,
				placeholder: {
					id: '-1', 
					text: '[ Select User ]'
				},
				allowClear: true,
				templateResult: function(item) {
					return item.loading ? item.text : $("<span>" + item.html + "</span>");
				},
				templateSelection: function(item) {
					return item.text;
				}
			});
		}
	


		// Handle media select, upload and sorting

		$( "#al_author_gallery_current" ).sortable({
			handle: '.al_author_gallery_image img',
		}).disableSelection();;
    	

		// Add Profile Picture
		
		$(document).on( 'click', '#al_author_profile_pic_add', function(e){
			e.preventDefault();
			let $this = $(this),
				custom_uploader = wp.media({
				title: 'Browse',
				library : {
					type : 'image'
				},
				button: {
					text: 'Set image as profile picture'
				},
				multiple: false
			}).on('select', function() { // it also has "open" and "close" events
				
				let attachment = custom_uploader.state().get('selection').first().toJSON();
				
				$('#al_author_profile_pic_wrapper').prepend('<img src="' + attachment.url + '" width="150" class="al_author_profile_pic_thumbnail" />');
				
				$('#al_author_profile_pic').val(attachment.id);

				$this.replaceWith('<p><input type="button" class="button-secondary button-large button" id="al_author_profile_pic_remove" name="al_author_profile_pic_remove" value="Remove Image" /></p>');

			}).open();
		});
		

		// Remove Profile Picture

		$(document).on('click', '#al_author_profile_pic_remove', function(e){
			e.preventDefault();
			
			if (!confirm('Are you sure you want to remove the profile picture?')) {
				return false;
			}

			$('#al_author_profile_pic').val(''); 
			$('.al_author_profile_pic_thumbnail').remove();

			$(this).replaceWith('<p><input type="button" class="button-primary button-large button" id="al_author_profile_pic_add" name="al_author_profile_pic_add" value="Add Image" /></p>');
		});


		// Add Gallery Images

		$(document).on( 'click', '.additional-image-upload', function(e){
			e.preventDefault();
			var additionalImageId = $(this).data('additional-id');
			var custom_uploader = wp.media({
				title: 'Browse',
				library : {
					type : 'image'
				},
				button: {
					text: 'Add image to Galley' // button label text
				},
				multiple: false
			}).on('select', function() { // it also has "open" and "close" events
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#additionalImageThumb' + additionalImageId).html('<img src="' + attachment.url + '" width="150" class="al_author_gallery_image" />');
				$('#additional_image_id_'+additionalImageId).val(attachment.id);
			}).open();
		});
	

		// Remove Gallery Images

		$(document).on('click', '.additional-image-remove', function(e){
			e.preventDefault();
			if (!confirm('Are you sure you want to remove this image?')) {
				return false;
			}
			var additionalImageId = $(this).data('additional-id');
			$('#additional_image_id_'+additionalImageId).val(''); // emptying the hidden field
			$('#additionalImageThumb' + additionalImageId).html('');
			$('#al_author_gallery_new'+additionalImageId).remove();
		});


		
	});

})( jQuery );
