<?php get_header(); ?>

<section id="al_authors_wrapper">

<h1>Authors</h1>

<ul id="al_authors_archive_list">

<?php 
while ( have_posts() ) : the_post();
    
    echo '<li>';

    global $author_data;
    $author_data = al_get_authordata();
    
    al_the_author_image(); 
    echo '<h2><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
    
    echo '</li>';
endwhile; // End of the loop. ?>

</ul>

</section>

<?php get_footer(); ?>