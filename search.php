<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php the_excerpt( '<span class="read-more">Read more &raquo;</span>' ); ?>
	<?php endwhile; ?>
	<?php echo km_page_navi(); ?>
	<?php else : ?>
	<?php endif; ?>
<?php get_footer(); ?>