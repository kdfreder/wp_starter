<?php
//here's a way to see if an array key exists anywhere in a nested array
function nested_key_exists(array $arr, $key){
	if (array_key_exists($key, $arr)){
		return true;
	}
	foreach ($arr as $element){
		if (is_array($element)){
			if (nested_key_exists($element, $key)){
				return true;
			}
		}
	}
	return false;
}

//we can use this to check if a value exists anywhere within a multideimensional array
function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}

//here's a way to check if any of the values in an array contain a string
function array_contains_sting(array $arr, $str){
	foreach($arr as $a){
		if (stripos($str,$a) !== false){
			return true;
		}
	}
	return false;
}

//simple way to limit plain text, you'll be happier if you don't put html in this
function limit_text($text, $limit){
	if (str_word_count($text, 0) > $limit){
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]);
		$text = trim($text);
	}
	return $text;
}

//so you limited the text, but now you want to display the rest?
function rest_of_text($text, $start){
	if (str_word_count($text, 0) > $start){
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, $pos[$start]);
	}
	return $text;
}

//if you want to keep the case but clean it a little bit
function clean_my_string($string, $hyphenate = true){
	$string = preg_replace('/[^A-Za-z0-9\s]/', ' ', $string);
	if($hyphenate){
		$string = str_replace(' ', '-', $string);
	}
	return $string;
}

//if you need to turn a simple or complex string into a usable slug
function slug_it($string, $sep = '-'){
	$string = strip_tags($string);
	$string = html_entity_decode($string);
	$string = urldecode($string);
	$string = preg_replace('/[^A-Za-z0-9 ]/', ' ', $string);
	$string = preg_replace('/ +/', ' ', $string);
	$string = trim($string);
	$string = strtolower($string);
	$string = str_replace(' ', $sep, $string);
	return $string;
}

//check if a string starts with a differnt string
function starts_with($haystack, $needle){
	return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== false;
}

//check if a string ends with a differnt string
function ends_with($haystack, $needle){
	return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function get_google_directions($destination){
	$destination = str_replace('<br/>', '+', $destination);
	$destination = str_replace('<br />', '+', $destination);
	$destination = str_replace(' ', '+', $destination);
	$destination = str_replace(',', '%2C', $destination);
	$dir_url = 'https://www.google.com/maps/dir/?api=1&destination='.$destination;
	return $dir_url;
}

//get the youtube id from a url
function get_youtube_id($url){
	$id = false;
	if(strpos($url, 'youtube') !== false){
		$parsed = parse_url($url);
		parse_str($parsed['query'], $query);
		$id = $query['v'];
	}
	if(strpos($url, 'youtu.be') !== false){
		$parsed = parse_url($url);
		$path = explode('/', $parsed['path']);
		$id = $path[1];
	}
	return $id;
}

//get the vimeo id from a url
function get_vimeo_id($url){
	$id = false;
	if(strpos($url, 'vimeo') !== false){
		$parsed = parse_url($url);
		$path = explode('/', $parsed['path']);
		$id = $path[1];
		//is the video is set to private, it could have a secret string that it needs as well
		if(isset($path[2])){
			$id .= '/'.$path[2];
		}
	}
	return $id;
}

//usually we seperate out our youtube embeds, but if we want to put one in a wysiwyg let's make it look a little better
//requires either an id or url attr [embed_youtube id=### or url=&&&]
function cus_embed_youtube($atts){
	if(isset($atts['id'])){
		return '<div class="vid-box"><div class="vid-holder"><iframe src="https://www.youtube.com/embed/'.$atts['id'].'?showinfo=0&autohide=1&rel=0" frameborder="0" allowfullscreen></iframe></div></div>';
	}
	if(isset($atts['url'])){
		$id = get_youtube_id($url);
		if($id){
			return '<div class="vid-box"><div class="vid-holder"><iframe src="https://www.youtube.com/embed/'.$id.'?showinfo=0&autohide=1&rel=0" frameborder="0" allowfullscreen></iframe></div></div>';
		}
	}
}
add_shortcode('embed_youtube', 'cus_embed_youtube');

//usually we seperate out our youtube embeds, but if we want to put one in a wysiwyg let's make it look a little better
//requires either an id or url attr [embed_vimeo id=### or url=&&&]
function cus_embed_vimeo($atts){
	if(isset($atts['id'])){
		return '<div class="vid-box"><div class="vid-holder"><iframe src="https://player.vimeo.com/video/'.$id.'?title=0&byline=0&portrait=0" frameborder="0" allowfullscreen></iframe></div></div>';
	}
	if(isset($atts['url'])){
		$id = get_vimeo_id($url);
		if($id){
			return '<div class="vid-box"><div class="vid-holder"><iframe src="https://player.vimeo.com/video/'.$id.'?title=0&byline=0&portrait=0" frameborder="0" allowfullscreen></iframe></div></div>';
		}
	}
}
add_shortcode('embed_vimeo', 'cus_embed_vimeo');

//gives us data that we can use for a vimeo, including a link to it's thumbnail
function get_vimeo_info($id) {
	//we need the referer if the videos are private
	$referer = get_site_url();
	$opts = array(
		'http'=>array(
			'header'=>"Referer: $referer\r\n"
		)
	);
	$context = stream_context_create($opts);
	$data = file_get_contents("https://vimeo.com/api/oembed.json?url=https://vimeo.com/".$id, false, $context);
	
	if($data === FALSE){
		return FALSE;
	} else {
		$data = json_decode($data);
		return $data;
	}
}

//constantly getting thumbnails from vimeo videos can be intesive. it's nice to try and save them to our site instead
//$field is the field we want to save that image to.
//if that field happens to be a sub field, it will have to be passed as an array like: array('repeater_name', [the index], 'field_name')
//you can include repeater names and indexes as needed for nested repeaters
function maybe_get_vimeo_thumb($post_id, $field, $vimeo_url, $url_or_tag = 'tag'){
	// check if the field is a sub field, it would have to be passed in an array format like: array('repeater', 1, 'sub_repeater', 2, 'sub_sub_field')
	if(is_array($field)){
		$field_check = implode('_', $field);
	} else {
		$field_check = $field;
	}
	// check if we've already saved an image before
	if(get_post_meta($post_id, $field_check, true) != null){
		//we will have value in the form of an attachment id
		if($url_or_tag == 'tag'){
			$img = '<img src="'.get_field($field_check, $post_id)['sizes']['lo-res'].'" alt="'.get_field($field_check, $post_id)['alt'].'" />';
		} elseif($url_or_tag == 'url'){
			$img = get_field($field_check, $post_id)['sizes']['lo-res'];
		}
		return $img;
	}
	$id = get_vimeo_id($vimeo_url);
	$data = get_vimeo_info($id);
	if($data !== false){
		$thumb = substr($data->thumbnail_url, 0, strpos($data->thumbnail_url, "_"));
		$thumb .= '_640.jpg';
		// Gives us access to the download_url() and wp_handle_sideload() functions
		if ( !function_exists('wp_handle_upload') ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$url = $thumb;
		$timeout_seconds = 20;
		// Download file to temp dir
		$temp_file = download_url( $url, $timeout_seconds );
		if ( !is_wp_error( $temp_file ) ) {
			// Array based on $_FILE as seen in PHP file uploads
			$file = array(
				'name'	 => basename($url), // ex: wp-header-logo.png
				'type'	 => 'image/png',
				'tmp_name' => $temp_file,
				'error'	=> 0,
				'size'	 => filesize($temp_file),
			);
			$overrides = array(
				// Tells WordPress to not look for the POST form
				// fields that would normally be present as
				// we downloaded the file from a remote server, so there
				// will be no form fields
				'test_form' => false,
				// Setting this to false lets WordPress allow empty files, not recommended
				'test_size' => true,
			);
			// Move the temporary file into the uploads directory
			$results = wp_handle_sideload( $file, $overrides );
			if ( !empty( $results['error'] ) ) {
				// Insert any error handling here
			} else {
				$filename  = $results['file']; // Full path to the file
				$local_url = $results['url'];  // URL to the file in the uploads dir
				$type	  = $results['type']; // MIME type of the file
				$wp_upload_dir = wp_upload_dir();
				$attachment = array(
					'guid' => $wp_upload_dir['url'] . '/' . basename($results['file']),
					'post_mime_type' => $results['type'],
					'post_title' => preg_replace( '/\.[^.]+$/', '', basename($results['file']) ),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment($attachment, $results['file']);
				if ( !function_exists('wp_generate_attachment_metadata') ) {
					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
				}
				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $results['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );
		
				// Assign the file to the field
				if(is_array($field)){
					update_sub_field($field, $attach_id, $post_id);
				} else {
					update_field($field, $attach_id, $post_id);
				}
			}
		}
		if($url_or_tag == 'tag'){
			$img = '<img src="'.get_field($field_check, $post_id)['sizes']['lo-res'].'" alt="'.get_field($field_check, $post_id)['alt'].'" />';
		} elseif($url_or_tag == 'url'){
			$img = get_field($field_check, $post_id)['sizes']['lo-res'];
		}
		return $img;
	} else {
		if($url_or_tag == 'tag'){
			$img = '<img src="'.get_template_directory().'/library/img/default-vimeo-thumbnail.png" alt="Default Video Thumb" />';
		} elseif($url_or_tag == 'url'){
			$img = get_template_directory().'/library/img/default-vimeo-thumbnail.png';
		}
		return false;
	}
}

//here's how we can tie in and make an excerpt if we don't have an excerpt or wp_content
//you can add the needed fields in functions
add_filter('get_the_excerpt', function($excerpt, $post) use ($cus_acf_excerpt_fields) {
	$fields_to_check = $cus_acf_excerpt_fields; // add more as you need
	if($excerpt == ''){
		foreach($fields_to_check as $field){
			if(get_post_meta( $post->ID, $field, true )) {
				$excerpt = get_post_meta( $post->ID, $field, true );
				break;
			}
		}
		if($excerpt != ''){ //check if it's still empty
			$excerpt = strip_shortcodes( $excerpt );
			$regex = '#(<h([1-6])[^>]*>)\s?(.*)?\s?(<\/h\2>)#';
			$excerpt = preg_replace($regex,'', $excerpt);
			$excerpt = apply_filters('the_content', $excerpt);
			$excerpt = str_replace(']]&gt;', ']]&gt;', $excerpt);
			$excerpt_length = apply_filters('excerpt_length', cus_excerpt_length()); //you can edit this in functions, or here if you need it different
			$excerpt_more = apply_filters('excerpt_more', cus_excerpt_more());
			$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
		}
	}
	return $excerpt;
}, 10, 2);

// this helps for when we want to use get_template_part in an ajax call
function load_template_part($template_name, $part_name=null) {
	ob_start();
	get_template_part($template_name, $part_name);
	$var = ob_get_contents();
	ob_end_clean();
	return $var;
}

//yoast adds a thing for 'primary categories', they don't really mean much outside yoast but we can tap into them
function get_primary_category(){
	$primary_cat_id = get_post_meta(get_the_ID(),'_yoast_wpseo_primary_category', true);
	if($primary_cat_id){
		$cat = get_category(intval($primary_cat_id));
		if(isset($cat->name)){
			$cat_name = $cat->name;
		}
	} else {
		$cats = wp_get_post_categories(get_the_ID(), array('fields' => 'all'));
		$cat_name = $cats[0]->name;
	}
	return $cat_name;
}

//i've only used this a couple times, but it can be helpful
function get_category_by_url($url) {
	foreach( (get_categories()) as $category) {
		if ( get_category_link($category->cat_ID) == $url ){
			return $category->cat_ID;
		}
	}
	return false;
}

//recursivly returns children of a post as an array
function get_posts_children($parent_id, $post_type){
	$children = array();
	$posts = get_posts( array( 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => $post_type, 'post_parent' => $parent_id, 'suppress_filters' => false ));
	foreach( $posts as $child ){
		array_push($children, $child->ID);
		$gchildren = get_posts_children($child->ID, $post_type);
		if( !empty($gchildren) ) {
			$children = array_merge($children, $gchildren);
		}
	}
	return $children;
}

// retrieves posts of a specific post_type and taxonomy, sorted by term
function get_taxonomy_posts($post_type, $taxonomy, $parent_term = ''){
	if(!$post_type || !$taxonomy){
		return false;
	}
	global $wpdb;
	//if $parent_term is defined and is not an integer then should be a slug, so let's get its id
	if(!empty($parent_term) && !is_int($parent_term)){
		$parent_data = get_term_by('slug',$parent_term,$taxonomy);
		$parent_term = $parent_data->term_id;
	}
	$term_ids = get_terms($taxonomy, array( 'parent' => $parent_term, 'fields' => 'ids' ));
	if(is_wp_error($term_ids)) return false;
	$term_ids_str = implode(',',$term_ids);

	// NOTE: terms.term_order gets added to tables by this plugin:
	// http://wordpress.org/plugins/taxonomy-terms-order/
	$query = $wpdb->prepare(
	"select p.ID, p.post_title, p.post_content, p.post_excerpt, t.term_id, t.name as term_name, t.slug as term_slug, tt.count
	from " . $wpdb->prefix . "posts p
	inner join " . $wpdb->prefix . "term_relationships rel on p.ID = rel.object_id
	inner join " . $wpdb->prefix . "term_taxonomy tt on tt.term_taxonomy_id = rel.term_taxonomy_id
	inner join " . $wpdb->prefix . "terms t on tt.term_id = t.term_id
	where p.post_type = %s and p.post_status = %s and t.term_id in (" . $term_ids_str . ")
	"
	,$post_type
	,"publish"
	);

	$records = $wpdb->get_results($query);
	$posts_array = array();
	
	foreach($records as $record){
		$term_name = $record->term_name;
		if(!key_exists($term_name, $posts_array)){
			$posts_array[$term_name] = array();
		}
		$posts_array[$term_name][] = $record;
	}
	
	return $posts_array;
}

//let's us easily make a button if they gave the fields for it
function if_button($button_link, $button_text, $classes = ''){
	if(get_sub_field($button_link)){
		$link = get_sub_field($button_link);
	} else if(get_field($button_link)){
		$link = get_field($button_link);
	} else {
		$link = false;
	}
	if(get_sub_field($button_text)){
		$text = get_sub_field($button_text);
	} else if(get_field($button_text)){
		$text = get_field($button_text);
	} else {
		$text = false;
	}
	if($link && $text){
		return '<a href="'.$link.'" class="btn '.$classes.'">'.$text.'</a>';
	}
}

//sometimes we give a repeater of buttons, and this just speeds things up
function if_buttons($link, $text){
	if( have_rows('buttons') ):				
		while ( have_rows('buttons') ) : the_row();	
			echo if_button($link, $text);
		endwhile;
	endif;
}

//let's us easily check if a field exists to display it, and can wrap it up if we'd like
function if_field($field, $args = array()){
	if(is_null($field)){
		return false;
	}
	global $post;
	$defaults = array(
		'post_id' => $post->ID,
		'wrap' => null,
		'wrapclass' => null,
		'else' => null,
	);
	foreach($defaults as $k => $v){
		if(!isset($args[$k])){
			$args[$k] = $v;
			${$k} = $v;
		} else {
			${$k} = $args[$k];
		}
	}
	if(is_string($field)){
		if(get_sub_field($field)){ // always in context, don't need post
			$field = get_sub_field($field);
		} else if(get_field($field, intval($post_id))){ // could check for other pages value
			$field = get_field($field, intval($post_id));
		} else {
			if(!is_null($else)){
				$field = $else;
			} else {
				return false;
			}
		}
	}
	if($field){
		$echo = $field;
		if(!is_null($wrap)){
			$echo = "<".$wrap.">".$echo."</".$wrap.">";
		}
		if(!is_null($wrapclass)){
			$echo = str_replace("<".$wrap.">", "<".$wrap." class='".$wrapclass."'>", $echo);
		}
		return $echo;
	}
}

//like what we do for fields, but for returnable array or object items
function if_index($index, $wrap = null, $wrapclass = null){
	if(isset($index) && (gettype($index) == 'integer' || gettype($index) == 'double' || gettype($index) == 'string')){
		if(!is_null($wrap)){
			$index = "<".$wrap.">".$index."</".$wrap.">";
		}
		if(!is_null($wrapclass)){
			$index = str_replace("<".$wrap.">", "<".$wrap." class='".$wrapclass."'>", $index);
		}
		return $index;
	}
	return false;
}

//here we do something similar for acf image fields. all images will always be wrapped in .img-holder
function if_image($field, $args = array()){
	global $post;
	$defaults = array(
		'size' => 'lo-res',
		'classes' => '',
		'link' => false,
		'link_args' => array(
			'href' => '#',
			'attrs' => array() //put things like 'download' => 'download' or 'target' => '_blank' in here
		),
	);
	foreach($defaults as $k => $v){
		if(!isset($args[$k])){
			$args[$k] = $v;
		}
	}
	if(is_array($field)){
		$field = $field;
	} else if(get_sub_field($field)) {
		$field = get_sub_field($field);
	} else if(get_field($field)) {
		$field = get_field($field);
	}
	if(!empty($field)){
		$img = '<img src="'.$field['sizes'][$size].'" alt="'.$field['alt'].'" />';
		if($args['link']){
			$attrs = '';
			if(!empty($args['link_args']['attrs'])){
				foreach($args['link_args']['attrs'] as $k => $v){
					$attrs .= $k.'="'.$v.'" ';
				}
			}
			return '<a href="'.$args['link_args']['href'].'" class="img-holder '.$args['classes'].'" '.$attrs.'>'.$img.'</a>';
		} else {
			return '<div class="img-holder '.$args['classes'].'">'.$img.'</div>';
		}
	}
	return false;
}

//this helps if you use the table field add-on with acf
function make_acf_table($field){
	if ( $field ) {
		echo '<table border="0">';
			if ( $field['header'] ) {
				echo '<thead>';
					echo '<tr>';
						foreach ( $field['header'] as $th ) {
							echo '<th>';
								echo $th['c'];
							echo '</th>';
						}
					echo '</tr>';
				echo '</thead>';
			}
			echo '<tbody>';
				foreach ( $field['body'] as $tr ) {
					echo '<tr>';
						foreach ( $tr as $td ) {
							echo '<td>';
								echo $td['c'];
							echo '</td>';
						}
					echo '</tr>';
				}
			echo '</tbody>';
		echo '</table>';
	}
}

//say you want to get the first(only) result of a post type with a certain meta value
function get_post_by_meta($post_type, $meta_name, $meta_value){
	$args = array(
		'meta_query' => array(
			array(
				'key' => $meta_name,
				'value' => $meta_value
			)
		),
		'post_type' => $post_type,
		'posts_per_page' => '1'
	);
	$posts = get_posts($args);
	if ( ! $posts || is_wp_error( $posts ) ){
		return false;
	}
	return $posts[0];
}
/* DON'T DELETE THIS CLOSING TAG */ ?>