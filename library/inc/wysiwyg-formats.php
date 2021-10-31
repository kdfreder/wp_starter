<?php
function remove_default_format_select( $buttons ) {
    //Remove the format dropdown select and text color selector
    $remove = array( 'formatselect' );

    return array_diff( $buttons, $remove );
 }
add_filter( 'mce_buttons', 'remove_default_format_select' );


// Callback function to insert 'styleselect' into the $buttons array
function my_new_mce_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons', 'my_new_mce_buttons' );


// here we'll define all the different formats that could be available
function my_mce_before_init_insert_formats( $init_array ) {
	
    // Define the style_formats array
    $style_formats = array(
            array(
                'title' => 'Paragraph',
                'block' => 'p'
                ),
            /*array(
                'title' => 'Heading 1',
                'block' => 'h1'
                ),*/
            array(
                'title' => 'Heading 2',
                'block' => 'h2'
                ),
            array(
                'title' => 'Heading 3',
                'block' => 'h3'
                ),
            array(
                'title' => 'Heading 4',
                'block' => 'h4'
                ),
            array(
                'title' => 'Callout Text',
                'block' => 'p',
                'classes' => 'callout',
                ),
            array(
                'title' => 'Subtitle',
                'block' => 'div',
                'classes' => 'subtitle',
                //'wrapper' => true,
            ),
        );
    $init_array['preview_styles'] = $init_array['preview_styles'].' letter-spacing color';   
    $init_array['style_formats'] = json_encode( $style_formats );  

    return $init_array;  

} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats');


function hwid_acf_admin_footer() {
	?>
	<script type="text/javascript">
		(function($) {
	acf.add_filter('wysiwyg_tinymce_settings', function(mceInit, id) {
		var mceInitElements = $('#' + mceInit.id);
		var acfEditorField = mceInitElements.closest('.acf-field[data-type="wysiwyg"]');
		var repeater = mceInitElements.closest('.acf-field-repeater');
		var repeaterName = repeater.data('name');
		var group = mceInitElements.closest('.acf-field-group');
		var groupName = group.data('name');
		var fieldKey = acfEditorField.data('key');
		var fieldName = acfEditorField.data('name');
	    var flexContentName = mceInitElements.parents('[data-type="flexible_content"]').first().data('name');
		var layoutName = mceInitElements.parents('[data-layout]').first().data('layout');
		mceInit.body_class += " acf-field-key-" + fieldKey;
		mceInit.body_class += " acf-field-name-" + fieldName;
		if (flexContentName) {
			mceInit.body_class += " acf-flex-name-" + flexContentName;
		}
		if (layoutName) {
			mceInit.body_class += " acf-layout-" + layoutName;
		}
		if(repeater.length > 0) {
			mceInit.body_class += " acf-repeater-child repeater-" + repeaterName;
		}
		if(group.length > 0) {
			mceInit.body_class += " acf-group-child group-" + groupName;
		}
		/* examples:
		var formats = [
			{title: "Paragraph", block: "p", name: "custom0", deep: true, split: true},//formats[0]
			{title: "Subtitle", block: "div", classes: ["subtitle"], name: "custom1", deep: true, split: true, styles: {color: '#74AA50'}},//formats[1]
			{title: "Heading", block: "h2", name: "custom2", deep: true, split: true, styles: {color: '#784E90'}},//formats[2]
			{title: "Small Heading", block: "h3", name: "custom3", deep: true, split: true},//formats[3]
		];
		if(fieldName == 'content' && repeaterName == 'slider'){
			mceInit.style_formats = [
				{title: "Heading", block: "h2", name: "custom99", deep: true, split: true, styles: {color: '#FC9AA0'}},
				formats[0],
			];
		}
		*/
		return mceInit;
	});
})(jQuery);
	</script>
<?php
}
add_action('acf/input/admin_footer', 'hwid_acf_admin_footer');
?>