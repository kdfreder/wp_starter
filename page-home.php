<?php
/*
 Template Name: Home Page
*/
?>
<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<section>
		<div class="wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="the_content">
		<?php the_content(); ?>
	</section>
		
	<?php endwhile; endif; ?>
<?php get_footer(); ?>