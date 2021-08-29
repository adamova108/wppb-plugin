<?php get_header(); ?>

<section id="al_authors_wrapper">

<h1>Authors</h1>

<ul id="al_authors_archive_list">

<?php 
while ( have_posts() ) : 
    
    the_post(); 
    al_the_author_data();
    
    echo '<li>';

    al_the_author_image(); 
    al_the_author_title();
    al_the_author_bio_excerpt();
    al_the_author_social();
    al_the_author_profile_link();
    
    echo '</li>';

endwhile; // End of the loop. ?>

</ul>

</section>

<?php get_footer(); ?>