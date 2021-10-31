<?php
// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	// remove plugin dashboard boxes
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
}
add_action('wp_dashboard_setup', 'disable_default_dashboard_widgets');

function cus_admin_scripts_and_styles(){
	wp_enqueue_style('cus_admin_styles', get_template_directory_uri() . '/library/assets/admin.css', '' );
}
add_action( 'admin_enqueue_scripts', 'cus_admin_scripts_and_styles' );

//login items
function cus_login_css() {
	wp_enqueue_style('cus_login_css', get_template_directory_uri() . '/library/assets/login.css', false);
}
add_action('login_enqueue_scripts', 'cus_login_css', 10);
function cus_login_url() {
	return home_url();
}
add_filter('login_headerurl', 'cus_login_url');
function cus_login_title() {
	return get_option('blogname');
}
add_filter('login_headertext', 'cus_login_title');

// Custom Backend Footer
function cus_custom_admin_footer() {
	return '';
}
add_filter('admin_footer_text', 'cus_custom_admin_footer');





//it can be cumbersome to always add these capabilities to editors,
//or have to install user role editor (good plugin) just to let editors add users and edit menus
//so we have this instead
function give_editors_some_permissions(){
	$role_object = get_role( 'editor' );
	//gives access to the menus
	$role_object->add_cap( 'edit_theme_options' );
	//allows maintaining users
	$role_object->add_cap('edit_users');
	$role_object->add_cap('list_users');
	$role_object->add_cap('promote_users');
	$role_object->add_cap('create_users');
	$role_object->add_cap('add_users');
	$role_object->add_cap('delete_users');
	//gives access to gravity forms
	$role_object->add_cap('gravityforms_edit_forms');
	$role_object->add_cap('gravityforms_delete_forms');
	$role_object->add_cap('gravityforms_create_form');
	$role_object->add_cap('gravityforms_view_entries');
	$role_object->add_cap('gravityforms_edit_entries');
	$role_object->add_cap('gravityforms_delete_entries');
	$role_object->add_cap('gravityforms_export_entries');
	$role_object->add_cap('gravityforms_view_entry_notes');
	$role_object->add_cap('gravityforms_edit_entry_notes');
	//common gravity form add-ons we've used
	//more can be added, but you'll have to do a quick theme switch for them to take effect
	$role_object->add_cap('gravityforms_activecampaign');
	$role_object->add_cap('gravityforms_constantcontact');
	$role_object->add_cap('gravityforms_mailchimp');
	//i don't add user registration because that can take a little more backend work
}
add_action("after_switch_theme", "give_editors_some_permissions");
//i don't know of a capability for redirection, but there is a filter
function redirection_roles($role){
	return 'edit_pages';
}
add_filter( 'redirection_role', 'redirection_roles' );
//now incase there are othe options in the appearnce menu still showing, lets make sure they don't
function hide_menu() {
	if (current_user_can('editor')) {
		remove_submenu_page( 'themes.php', 'themes.php' ); // hide the theme selection submenu
		remove_submenu_page( 'themes.php', 'widgets.php' ); // hide the widgets submenu
		remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Ftools.php' ); // hide the customizer submenu
		remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Ftools.php&#038;autofocus%5Bcontrol%5D=background_image' ); // hide the background submenu
	}
	remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
	remove_submenu_page( 'themes.php', 'theme-editor.php' );
}
add_action('admin_head', 'hide_menu');

// Hide all administrators from user list.
add_action('pre_user_query','isa_pre_user_query');
function isa_pre_user_query($user_search) {
	$user = wp_get_current_user();
	if ( ! current_user_can( 'manage_options' ) ) {
		global $wpdb;
		$user_search->query_where = 
			str_replace('WHERE 1=1', 
			"WHERE 1=1 AND {$wpdb->users}.ID IN (
				 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
					WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
					AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
			$user_search->query_where
		);
	}
}

//editors should not see admins in their list now, but just in case let's make sure they can't delete an admin still
class ISA_User_Caps {
	function __construct() {
		add_filter( 'editable_roles', array(&$this, 'editable_roles'));
		add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
	}
	// Remove 'Administrator' from the list of roles if the current user is not an admin
	function editable_roles( $roles ){
		if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
			unset( $roles['administrator']);
		}
		return $roles;
	}
	function map_meta_cap( $caps, $cap, $user_id, $args ){
		switch( $cap ){
			case 'edit_user':
			case 'remove_user':
			case 'promote_user':
				if( isset($args[0]) && $args[0] == $user_id )
					break;
				elseif( !isset($args[0]) )
					$caps[] = 'do_not_allow';
				$other = new WP_User( absint($args[0]) );
				if( $other->has_cap( 'administrator' ) ){
					if(!current_user_can('administrator')){
						$caps[] = 'do_not_allow';
					}
				}
				break;
			case 'delete_user':
			case 'delete_users':
				if( !isset($args[0]) )
					break;
				$other = new WP_User( absint($args[0]) );
				if( $other->has_cap( 'administrator' ) ){
					if(!current_user_can('administrator')){
						$caps[] = 'do_not_allow';
					}
				}
				break;
			default:
				break;
		}
		return $caps;
	}
} 
$isa_user_caps = new ISA_User_Caps();




//remove the customize, that's for stock themes with differnt display options
function cus_before_admin_bar_render(){
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('customize');
}
add_action('wp_before_admin_bar_render', 'cus_before_admin_bar_render');
function as_remove_menus (){
	global $submenu;
	// Appearance Menu
	unset($submenu['themes.php'][6]); // Customize
}
add_action('admin_menu', 'as_remove_menus');	

//show the favicon in the admin
function add_favicon(){
	$favicon_url = get_template_directory_uri() . '/library/src/img/favicons/favicon.ico';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');

//yoast can force it's way to the top sometimes
function yoasttobottom(){
	return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');

if(function_exists('acf_add_options_page')){
	acf_add_options_page(array(
		'page_title' 	=> 'Global/Theme Options',
		'menu_title'	=> 'Global/Theme Options',
		'menu_slug' 	=> 'global-theme-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'position' => 58,
	));
}

function super_category_toggler(){
	$taxonomies = apply_filters('super_category_toggler',array());
	for($x=0;$x<count($taxonomies);$x++)
	{
		$taxonomies[$x] = '#'.$taxonomies[$x].'div .selectit input';
	}
	$selector = implode(',',$taxonomies);
	if($selector == '') $selector = '.selectit input';
	
	echo '
		<script>
		jQuery("'.$selector.'").change(function(){
			var $chk = jQuery(this);
			var ischecked = $chk.is(":checked");
			$chk.parent().parent().siblings().children("label").children("input").each(function(){
var b = this.checked;
ischecked = ischecked || b;
})
			checkParentNodes(ischecked, $chk);
		});
		function checkParentNodes(b, $obj)
		{
			$prt = findParentObj($obj);
			if ($prt.length != 0)
			{
			 $prt[0].checked = b;
			 checkParentNodes(b, $prt);
			}
		}
		function findParentObj($obj)
		{
			return $obj.parent().parent().parent().prev().children("input");
		}
		</script>
		';
	
}
add_action('admin_footer-post.php', 'super_category_toggler');
add_action('admin_footer-post-new.php', 'super_category_toggler');

// Let's stop WordPress re-ordering my categories/taxonomies when I select them	
function stop_reordering_my_categories($args){
	$args['checked_ontop'] = false;
	return $args;
}
add_filter('wp_terms_checklist_args','stop_reordering_my_categories');

//editors normally don't need to see the yoast and other stuff
function cus_remove_yoast_admin_menu(){
	if(!current_user_can('activate_plugins')):
		remove_menu_page('cptui_manage_post_types');
		remove_menu_page('wpseo_dashboard');
		remove_menu_page('wpengine-common');
	endif;
}
add_action('admin_menu', 'cus_remove_yoast_admin_menu', 9999); 

function cus_admin_bar_yoast(){
	if (!(current_user_can( 'activate_plugins' ))){
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wpseo-menu');
	}
}
add_action('wp_before_admin_bar_render', 'cus_admin_bar_yoast');

function disable_seo_metabox(){
	remove_meta_box('wpseo_meta', 'post', 'normal');
	remove_meta_box('wpseo_meta', 'page', 'normal');
}

function wpse_init(){
	if(!(current_user_can( 'activate_plugins' ))){
		add_filter('wpseo_use_page_analysis', '__return_false');
		add_action('add_meta_boxes', 'disable_seo_metabox', 100000);
	}   
}
add_action('init', 'wpse_init');

//allow editors to add redirections
add_filter( 'redirection_role', 'redirection_to_editor' );
function redirection_to_editor() {
	return 'edit_pages';
}

//this will limit the exceprts so they don't get too long themselves
function excerpt_count_js(){
	//if ('page' != get_post_type()) {
		  echo '<script>jQuery(document).ready(function(){
		  if(jQuery("#postexcerpt, #oz_postexcerpt").length){
jQuery("#postexcerpt .handlediv, #oz_postexcerpt .handlediv").after("<div style=\"position:absolute;top:12px;right:34px;color:#666;\"><small>Excerpt length: </small><span id=\"excerpt_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 200</span><small><span style=\"font-weight:bold; padding-left:7px;\">character(s).</span></small></div>");
	 jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
	 jQuery("#excerpt").keyup( function() {
		 if(jQuery(this).val().length > 200){
			jQuery(this).val(jQuery(this).val().substr(0, 200));
		}
	 jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
	 
   });
   }
});</script>';
	//}
}
add_action( 'admin_head-post.php', 'excerpt_count_js');
add_action( 'admin_head-post-new.php', 'excerpt_count_js');

//i like putting the excerpt near the top, if you don't you don't need this stuff
function oz_remove_normal_excerpt() {
	remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
}
//add_action( 'admin_menu' , 'oz_remove_normal_excerpt' );
function oz_add_excerpt_meta_box( $post_type ) {
	if ( post_type_supports( $post_type, 'excerpt' ) ) {
		add_meta_box(
			'oz_postexcerpt',
			__( 'Excerpt', 'thetab-theme' ),
			'post_excerpt_meta_box',
			$post_type,
			'after_title',
			'high'
		);
	}
}
//add_action( 'add_meta_boxes', 'oz_add_excerpt_meta_box' );
function oz_run_after_title_meta_boxes() {
	global $post, $wp_meta_boxes;
	do_meta_boxes( get_current_screen(), 'after_title', $post );
}
//add_action( 'edit_form_after_title', 'oz_run_after_title_meta_boxes' );
//end of moving the excerpt stuff

/* DON'T DELETE THIS CLOSING TAG */ ?>