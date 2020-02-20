
<?php
/* 
Template Name: Archives
*/
get_header(); ?>
 
<div id="primary" class="site-content">
<div id="content" role="main"> 
 
<?php while ( have_posts() ) : the_post(); ?>
                
<h4 class="entry-title"><a href="<?= get_the_permalink ();?>"><?php the_title(); ?></a></h4>
 
<div  class="entry-content">
 
<?php the_content(); ?>

</div><!-- .entry-content -->
 
<?php endwhile; // end of the loop. ?>
 
</div><!-- #content -->
</div><!-- #primary -->
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>