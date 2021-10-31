<?php
add_action('admin_menu','remove_default_post_type');
function remove_default_post_type(){
	remove_menu_page('edit.php');
}

add_action('admin_menu', 'remove_menu'); 
function remove_menu () 
{
   remove_menu_page('edit.php');
}

add_action('admin_bar_menu', 'remove_wp_nodes', 999);
function remove_wp_nodes() 
{
    global $wp_admin_bar;   
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('new-link');
    $wp_admin_bar->remove_node('new-media');
}
?>