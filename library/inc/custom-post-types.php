<?php
// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'cus_flush_rewrite_rules' );
function cus_flush_rewrite_rules() {
	flush_rewrite_rules();
}

//uncomment this line once you have made your changes for your post type(s)
//add_action( 'init', 'register_theme_custom_post_types', 10 );
function register_theme_custom_post_types() {
	//bare minimum will have to change occurances of custom post below to what you need
	//i like to keep the slugs singular
	$slug = "custom_post";
	$labels = array(
		"name" => "Custom Posts",
		"singular_name" => "Custom Post",
		//there are more that can be found in the codex, but this is all we really need
	);
	$args = array(
		"label" => $labels['name'],
		"labels" => $labels,
		
		"hierarchical" => false,
		"has_archive" => true,
		
		"public" => true, //master varaible for if this should be found on the front or admin
		"publicly_queryable" => true, //should this have pages on the front end
		"show_ui" => true, //should users be able to edit/add more of these posts?
		"show_in_menu" => true, //show_ui needs to be true, this adds an item to the admin menu
		"menu_position" => 25, //usully between 25-60
		"menu_icon" => '', //dashicon or path to image
		"show_in_nav_menus" => false, //allow users to add these posts types to the nav menu
		
		"delete_with_user" => false,
		"show_in_rest" => true, //make sure is set to true if using gutenberg
		"exclude_from_search" => false,
		"rewrite" => array( 
			//"slug" => "swatch", //defaults to post type value. use this if you need to change how it looks in the url
			"with_front" => false //remove this is you want everything to be under /blog/ or whatever
		),
		//"query_var" => false, //you'll usually want this true (default to slug, or you can supply the change here). make it false if you need to do something special
		"supports" => array( 
			"title",
			//"editor",
			//"author",
			//"thumbnail",
			//"excerpt",
			//"comments",
			//"revisions",
			//"page-attributes"
		),
		"can_export" => "false"
		//capabilities can be added if you need them too, but generally default is fine
	);
	register_post_type( $slug, $args );
}


//uncomment this line once you have made your changes for your taxonomies
//add_action( 'init', 'register_theme_custom_taxonomies', 10 );
function register_theme_custom_taxonomies() {
	$slug = "custom_taxonomy";
	$post_typs = array(
		//must have at least one post type
		"custom_post",
	);
	$labels = array(
		"name" => "Custom Taxonomies",
		"singular_name" => "Custom Taxonomy",
		//there are more that can be found in the codex, but this is all we really need
	);
	$args = array(
		"label" => $labels['name'],
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true, //true for categories, false for tags
		"publicly_queryable" => true, //master varaible for if this should be found on the front or admin
		"show_ui" => true, //should users be able edit/add more terms
		"show_in_menu" => true, //show_ui needs to be true, this adds an item to the admin menu 
		"show_in_nav_menus" => false, //allow users to add term pages to the menu
		"show_in_quick_edit" => false,
		//"meta_box_cb" => false, //use this if you plan to use acf to apply the values
		"show_admin_column" => true,
		//"query_var" => false, //you'll usually want this true (default to slug, or you can supply the change here). make it false if you need to do something special
		"rewrite" => array( 
			//"slug" => "swatch", //defaults to post type value. use this if you need to change how it looks in the url
			"with_front" => false //remove this is you want everything to be under /blog/ or whatever
		),
	);	
	register_taxonomy( $slug, $post_typs, $args );
}

?>
