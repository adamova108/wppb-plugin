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
		}).disableSelection();
    	

		// Add Images

		$(document).on( 'click', '#al_author_profile_pic_add, .additional-image-upload', function(e){
			
			e.preventDefault();

			if ($(this).attr('id') === 'al_author_profile_pic_add') {
				
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
				}).on('select', function() { 
					
					let attachment = custom_uploader.state().get('selection').first().toJSON();
					
					$('#al_author_profile_pic_wrapper').prepend('<img src="' + attachment.url + '" width="150" class="al_author_profile_pic_thumbnail" />');
					
					$('#al_author_profile_pic').val(attachment.id);

					$this.replaceWith('<p><input type="button" class="button-secondary button-large button" id="al_author_profile_pic_remove" name="al_author_profile_pic_remove" value="Remove Image" /></p>');

				}).open();
			}
			else {

				let additionalImageId = $(this).data('additional-id'),
					custom_uploader = wp.media({
					title: 'Browse',
					library : {
						type : 'image'
					},
					button: {
						text: 'Add image to Gallery' 
					},
					multiple: false
				}).on('select', function() { 
					
					let attachment = custom_uploader.state().get('selection').first().toJSON();
					
					$('#additionalImageThumb' + additionalImageId).html('<img src="' + attachment.url + '" width="150" class="al_author_gallery_image" />');
					
					$('#additional_image_id_'+additionalImageId).val(attachment.id);

				}).open();
			}
		});
		

		// Remove Profile Picture

		$(document).on('click', '#al_author_profile_pic_remove, .additional-image-remove', function(e){
			
			e.preventDefault();
			
			if (!confirm('Are you sure you want to remove the profile picture?')) {
				return false;
			}

			if ($(this).attr('id') === 'al_author_profile_pic_remove') {

				$('#al_author_profile_pic').val(''); 
				$('.al_author_profile_pic_thumbnail').remove();

				$(this).replaceWith('<p><input type="button" class="button-primary button-large button" id="al_author_profile_pic_add" name="al_author_profile_pic_add" value="Add Image" /></p>');
			}
			else {

				var additionalImageId = $(this).data('additional-id');
				$('#additional_image_id_'+additionalImageId).val(''); 
				$('#additionalImageThumb' + additionalImageId).html('');
				$('#al_author_gallery_new'+additionalImageId).remove();
			}
		});
			
	});

})( jQuery );
