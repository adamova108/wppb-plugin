<?php get_header(); ?>

<section id="al_authors_wrapper">

<?php 
while ( have_posts() ) : 
    
    the_post(); 
    al_the_author_data();
	
    echo '<h1>'.get_the_title().'</h1>';
    
    al_the_author_image();
    al_the_author_social(); 
    al_the_author_bio(); 
    
    al_the_author_gallery();

    al_the_author_related_posts();
  
endwhile; // End of the loop. ?>

</section>

<?php get_footer(); ?>