<?php
function cus_head_cleanup(){
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	// links for adjacent posts- normally not needed, but if the next/previous post links are needed remove this
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	add_filter('style_loader_src', 'cus_remove_wp_ver_css_js', 9999);
	add_filter('script_loader_src', 'cus_remove_wp_ver_css_js', 9999);
}
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
function cus_remove_wp_ver_css_js($src){
	if (strpos($src, 'ver=')){
		$src = remove_query_arg('ver', $src);
	}
	return $src;
}
function cus_remove_wp_widget_recent_comments_style(){
	if (has_filter('wp_head', 'wp_widget_recent_comments_style')){
		remove_filter('wp_head', 'wp_widget_recent_comments_style');
	}
}
function cus_remove_recent_comments_style(){
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])){
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}
function cus_gallery_style($css){
	return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}
// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function cus_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

//this is how the js and css is added to the site
function cus_scripts_and_styles(){
  if (!is_admin()){
		wp_register_style('cus-stylesheet', get_stylesheet_directory_uri() . '/library/assets/style.css', array(), '', 'all');
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)){
			wp_enqueue_script('comment-reply');
		}
		wp_register_script('cus-js', get_stylesheet_directory_uri() . '/library/assets/script.js', array('jquery'), '', true);
		global $wp_query;
		wp_localize_script( 'cus-js', 'scripts', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'query_vars' => json_encode( $wp_query->query ),
		));
		wp_enqueue_style('cus-stylesheet');
		wp_enqueue_script('jquery');
		wp_enqueue_script('cus-js');
	}
}
//the actual theme support items
function cus_theme_support(){
	remove_theme_support( 'post-formats' );
	remove_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_theme_support('title-tag'); 
	add_theme_support('menus');
	register_nav_menus(
		array(
			'main-nav' => 'The Main Menu',
			'footer-links' => 'Footer Menu',
			//'topbar' => 'Topbar' //we normally have this, but sometimes we don't
		)
	);
	add_theme_support('html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption'
	));
}

//sometimes we want to keep the default posts but change their name
function cus_change_post_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'News';
	$submenu['edit.php'][5][0] = 'News';
	$submenu['edit.php'][10][0] = 'Add News';
	$submenu['edit.php'][16][0] = 'News Tags';
}
function cus_change_post_object() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'News';
	$labels->singular_name = 'News';
	$labels->add_new = 'Add News';
	$labels->add_new_item = 'Add News';
	$labels->edit_item = 'Edit News';
	$labels->new_item = 'News';
	$labels->view_item = 'View News';
	$labels->search_items = 'Search News';
	$labels->not_found = 'No News found';
	$labels->not_found_in_trash = 'No News found in Trash';
	$labels->all_items = 'All News';
	$labels->menu_name = 'News';
	$labels->name_admin_bar = 'News';
}
//add_action( 'admin_menu', 'cus_change_post_label' );
//add_action( 'init', 'cus_change_post_object' );

//our pagination, ready for ajax if needed
function cus_page_navi($loop = null){
	if($loop == null){
		global $wp_query;
	} else {
		$wp_query = $loop;
	}
	if(isset($_GET['paged'])){
		$the_page = $_GET['paged'];
	} else {
		$the_page = get_query_var('paged');
	}
	$bignum = 999999999;
	if ( $wp_query->max_num_pages <= 1 ){
		return;
	}
	$out = '<nav class="pagination">';
	$out .=  paginate_links( array(
	'base'		 => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
	'format'	   => '',
	'current'	  => max( 1, $the_page ),
	'total'		=> $wp_query->max_num_pages,
	'prev_text'	=> '<i class="lnr lnr-arrow-left"></i><span class="screen-reader-text">Previous</span>',
	'next_text'	=> '<i class="lnr lnr-arrow-right"></i><span class="screen-reader-text">Next</span>',
	'type'		 => 'list',
	'end_size'	 => 3,
	'mid_size'	 => 1
	) );
	$out .= '</nav>';
	return $out;
}

function my_acf_settings_row_index_offset( $row_index_offset ) {
	$row_index_offset = 0;
	return $row_index_offset;
}
add_filter('acf/settings/row_index_offset', 'my_acf_settings_row_index_offset');

function english_search_stemmer($query){
	if(!is_search() && !wp_doing_ajax()){
		return;
	}
	if(!isset($query->query['s'])){
		return;
	}
	$term = $query->query['s'];
	$len  = strlen( $term );
	$end1 = substr( $term, -1, 1 );
	if ( 's' === $end1 && $len > 3 ) {
		$term = substr( $term, 0, -1 );
	}
	$query->query['s'] = $term;
	$query->query_vars['s'] = $term;
	
	return $query;
}
//add_action('pre_get_posts', 'english_search_stemmer', 10, 1);

// Remove the Edit link from the admin bar
function cus_remove_admin_bar_edit_link() {
	if( ! current_user_can( 'administrator' ) ) {
		global $wp_admin_bar;
		if(is_home() || is_archive() || is_category()){
			$wp_admin_bar->remove_menu( 'edit' );
		}
	}
}
//add_action( 'wp_before_admin_bar_render', 'cus_remove_admin_bar_edit_link' );

function add_role_to_body($classes) {
	global $current_user;
	if($current_user->ID > 0){
		$user_role = $current_user->roles[0];
		if(is_admin()){
			$classes .= 'role-'. $user_role;
		} else {
			$classes[] = 'role-'. $user_role;
		}
	}
	return $classes;
}
add_filter('body_class','add_role_to_body');
add_filter('admin_body_class', 'add_role_to_body');

function website_remove($fields){
	if(isset($fields['url'])){
		unset($fields['url']);
	}
	return $fields;
}
add_filter('comment_form_default_fields', 'website_remove');

function cpt_parent_class( $classes ) {
	global $post;
	if ( is_single() && $post->post_parent == 0 ) {
		$classes[] = get_post_type().'-parent';
	}
	return $classes;
}
add_filter( 'body_class', 'cpt_parent_class' );

function ct_archive_class($classes) {
	$obj = get_queried_object();
	if(isset($obj->taxonomy)){
		if($obj->parent == 0){
			$classes[] = 'parent-term';
		} else {
			$classes[] = 'child-term';
		}
	}
	return $classes;
}
add_filter('body_class', 'ct_archive_class');






/* DON'T DELETE THIS CLOSING TAG */ ?>
