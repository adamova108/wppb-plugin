<?php

get_header();

while ( have_posts() ) :
	
    the_post();

    $authorData = al_get_authordata();
    ?>
	
    <h1><?php the_title();?></h1>
    
    <?php echo wpautop($authorData['al_author_bio']); ?>

    <?php var_dump($authorData); ?>
  
<?php 	
endwhile; // End of the loop.

get_footer();