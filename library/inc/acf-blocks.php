<?php
//acf is now working with gutenberg, here's a code example
/*
add_action('acf/init', 'my_acf_init');
function my_acf_init() {
	// check function exists
	if( function_exists('acf_register_block') ) {
		// register a testimonial block
		acf_register_block(array(
			'name'				=> 'testimonial',
			'title'				=> __('Testimonial'),
			'description'		=> __('A custom testimonial block.'),
			'render_callback'	=> 'my_acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'testimonial', 'quote' ),
		));
	}
}

make your fieldset...

function my_acf_block_render_callback( $block ) {
	// convert name ("acf/testimonial") into path friendly slug ("testimonial")
	$slug = str_replace('acf/', '', $block['name']);
	// include a template part from within the "template-parts/block" folder
	if( file_exists( get_theme_file_path("/template-parts/block/content-{$slug}.php") ) ) {
		include( get_theme_file_path("/template-parts/block/content-{$slug}.php") );
	}
}

//template file...
// get image field (array)
$avatar = get_field('avatar');
// create id attribute for specific styling
$id = 'testimonial-' . $block['id'];
// create align class ("alignwide") from block setting ("wide")
$align_class = $block['align'] ? 'align' . $block['align'] : '';
?>
<blockquote id="<?php echo $id; ?>" class="testimonial <?php echo $align_class; ?>">
	<p><?php the_field('testimonial'); ?></p>
	<cite>
		<img src="<?php echo $avatar['url']; ?>" alt="<?php echo $avatar['alt']; ?>" />
		<span><?php the_field('author'); ?></span>
	</cite>
</blockquote>
<style type="text/css">
	#<?php echo $id; ?> {
		background: <?php the_field('background_color'); ?>;
		color: <?php the_field('text_color'); ?>;
	}
</style>
<?php

style to hearts content...
*/

?>