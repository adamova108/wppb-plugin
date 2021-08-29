<?php get_header(); ?>

<section id="al_authors_wrapper">

<?php 
while ( have_posts() ) : the_post();
    
    global $author_data;
    $author_data = al_get_authordata();
    ?>
	
    <h1><?php the_title();?></h1>
    
    <?php al_the_author_image(); ?>
    
    <?php al_the_author_bio(); ?>

    <!-- 

     'al_author_link_user' => string '5' (length=1)
  'al_author_fb_url' => string 'https://www.facebook.com/me/' (length=28)
  'al_author_li_url' => string 'https://www.linkedin.com/in/adamluzsi/' (length=38)
  'al_author_profile_pic' => string '298' (length=3)
  'al_author_gallery' => 
    array (size=11)
      0 => string '125' (length=3)
      1 => string '92' (length=2)
      2 => string '58' (length=2)
      3 => string '121' (length=3)
      4 => string '15' (length=2)
      5 => string '75' (length=2)
      6 => string '304' (length=3)
      7 => string '298' (length=3)
      8 => string '116' (length=3)
      9 => string '386' (length=3)
      10 => string '17' (length=2)

     -->
  
<?php endwhile; // End of the loop. ?>

</section>

<?php get_footer(); ?>