/**
 * 
 * For defining scripts without a jQuery wrapper
 * 
 */

function al_add_new_image() {
	
	let imageHtml = "";
	
	imageHtml += '<div class="al_author_gallery_image_wrapper" id="al_author_gallery_new'+additionalImages+'">';
	imageHtml += '<div class="al_author_gallery_image" id="additionalImageThumb'+additionalImages+'"></div>';
	imageHtml += '<input type="hidden" name="al_author_gallery[]" id="additional_image_id_'+additionalImages+'" value=""/>';
	imageHtml += '<a href="#" title="Change image" class="additional-image-upload al-button dashicons-edit-large dashicons-before" data-additional-id="'+additionalImages+'"></a>';
	imageHtml += '<a href="#" title="Remove image" class="additional-image-remove al-button dashicons-no dashicons-before" data-additional-id="'+additionalImages+'"></a>';
	imageHtml += '</div>';

	jQuery('#al_author_gallery_current').append(imageHtml);
	jQuery('#al_author_gallery_current' + jQuery('#al_author_gallery_current .al_author_gallery_image_wrapper').length).fadeIn();
	jQuery('#al_author_gallery_new'+additionalImages+' .additional-image-upload').click();
	additionalImages++;
}