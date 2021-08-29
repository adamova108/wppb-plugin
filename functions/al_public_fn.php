<?php
/**
 * Functions for public use (mainly the front-end)
 */

 
function al_get_authordata(int $author_ID = null) {

    global $post;

    return is_null($author_ID) ? 
        (isset($post->ID) && get_post_type($post->ID) == 'authors' ? Al_Author_Admin::get_author_custom($post->ID) : []) 
        : Al_Author_Admin::get_author_custom($author_ID);
}


function al_get_author_field(string $field = '', array $author_data = []) {

    if ($field == '') return '';

    global $author_data;
    
    return isset($author_data['al_author_'.$field]) ? $author_data['al_author_'.$field] : '';

}


function al_get_author_image(array $author_data = []) {
    
    global $author_data;
    
    $author_image = al_get_author_field('profile_pic');

    if (!is_numeric($author_image)) {
        return '';
    }

    $attachment_image_url = wp_get_attachment_url($author_image);

    return $attachment_image_url;
}


function al_the_author_image(array $author_data = []) {
    
    global $author_data;
    
    $attachment_image_url = al_get_author_image($author_data);

    echo $attachment_image_url != '' ? "<img class='al_author_profile_pic' src='$attachment_image_url' />" : "";
}


function al_the_author_bio() {
    
    global $author_data;

    echo '<article id="al_author_bio">'.wpautop(al_get_author_field('bio')).'</article>';
}