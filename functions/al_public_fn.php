<?php

function al_get_authordata(int $author_ID = null) {

    global $post;

    return is_null($author_ID) ? 
        (isset($post->ID) && get_post_type($post->ID) == 'authors' ? Al_Author_Admin::get_author_custom($post->ID) : []) 
        : Al_Author_Admin::get_author_custom($author_ID);
}