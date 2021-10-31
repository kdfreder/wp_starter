<?php
require_once('library/inc/support.php');
require_once('library/inc/admin.php');
//define('DISALLOW_FILE_EDIT', true); //wpengine typically causes an error here, if not on wpengine uncomment this

function get_the_party_started(){
	add_editor_style(get_stylesheet_directory_uri() . '/library/assets/editor-style.css');
	add_action('init', 'cus_head_cleanup');
	add_action( 'init', 'disable_emojis' );
	add_filter('wp_head', 'cus_remove_wp_widget_recent_comments_style', 1);
	add_action('wp_head', 'cus_remove_recent_comments_style', 1);
	add_filter('gallery_style', 'cus_gallery_style');
	add_action('wp_enqueue_scripts', 'cus_scripts_and_styles', 999);
	cus_theme_support();
	cus_update_default_image_sizes();
	add_filter('the_content', 'cus_filter_ptags_on_images');
	add_filter('excerpt_more', 'cus_excerpt_more');
}
add_action('after_setup_theme', 'get_the_party_started');

//you can use the cptui plugin or this file if you'd like
//require_once('assets/inc/custom-post-types.php');

//if you want to remove comments, uncomment the next line
require_once('library/inc/no-comments.php');

//if we don't need the default posts we can uncomment this
//require_once('library/inc/no-posts.php');

//if we can, it's nice to make the wysiwyg's look like the front end
//require_once('library/inc/wysiwyg-formats.php');

//or if you want we can use gutenberg and acf blocks
//require_once('library/inc/acf-blocks.php');

function cus_excerpt_length($length = null){
	return 22;
}
add_filter('excerpt_length', 'cus_excerpt_length', 999);

function cus_excerpt_more($more = null){
	return '... <a href="'.get_the_permalink().'" class="read-more" rel="nofollow">Read More</a>';
}
add_filter('excerpt_more', 'cus_excerpt_more', 999);

$cus_acf_excerpt_fields = array('excerpt', 'content');

//here is a file of functions we use a bit on other projects and are just good to have
require_once('library/inc/helpful-functions.php');

//sometimes we don't want to use the featured images on certain post types so we remove them to clean up the admin
//if we don't want them at all use remove_theme_support in library/inc/support
function remove_featured_images_from_pages(){
	//example
	remove_theme_support('post-thumbnails');
	//example
	add_theme_support('post-thumbnails', array('post', 'case_study'));
	set_post_thumbnail_size(125, 125, true); //this depends on the site 170 is good sometimes
}
//add_action('after_setup_theme', 'remove_featured_images_from_pages', 11); 

//if there is a plugin we don't want to get updated, use this
function disable_plugin_updates( $value ) {
	if ( isset($value) && is_object($value) ) {
		if ( isset( $value->response['theme-my-login/theme-my-login.php'] ) ) {
			unset( $value->response['theme-my-login/theme-my-login.php'] );
		}
	}
	return $value;
}
//add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

/************* THUMBNAIL SIZE OPTIONS *************/
function cus_update_default_image_sizes(){
	//these are images sizes that wordpress is going to make no matter what, so let's use them
	//uncomment them to change their settings from the current (default)
	
	update_option( 'thumbnail_size_w', 170 );
	update_option( 'thumbnail_size_h', 170 );
	//update_option( 'thumbnail_crop', 1 );
	
	update_option( 'medium_size_w', 400 ); 
	update_option( 'medium_size_h', 400 );
	
	//update_option( 'medium_large_size_w', 768 );
	//update_option( 'medium_large_size_h', 0 ); //0 allows any height
	
	//update_option( 'large_size_w', 1024 );
	//update_option( 'large_size_h', 1024 );
}
add_image_size('lo-res', 1440, 1440, false); // this lets us get a resonable sized image, it will not crop, but scale the image down to within this square

//sometimes we need to have a different title for the page then the one in the admin, so we use a custom field
function display_title_filter($title){
	$field = 'display_title'; //add your field here
	if(in_the_loop()){
		global $post;
		if(get_field($field, $post->ID)){
			$title = get_field($field, $post->ID);
		}
	}
	return $title;
}
add_filter('the_title', 'display_title_filter', 10, 1);

//if we want to control what buttons are available on the wysiwyg toolbar
function cus_tinymce_toolbars( $toolbars ){
	$toolbars['Basic'][1] = array('bold','italic','underline','blockquote','hr','strikethrough','bullist','numlist','alignleft','aligncenter','alignright','undo','redo','link','fullscreen');
	return $toolbars;
}
//add_filter( 'acf/fields/wysiwyg/toolbars' , 'cus_tinymce_toolbars'  );

//if we want multiple posts types to appear on the main search page
function cus_search_filter($query) {
	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search) {
			$query->set('post_type', array( 'post', 'page', 'module', 'event' ) );
		}
	}
}
//add_action('pre_get_posts','cus_search_filter');

/* DON'T DELETE THIS CLOSING TAG */ ?>