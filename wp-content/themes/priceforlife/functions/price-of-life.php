<?php

/* ---------------------------------------------------------------------- */
/*	Enqueue Price of Life Custom CSS
/* ---------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function(){
    wp_register_style('priceoflife', SS_BASE_URL . 'css/priceoflife.css', array('ss-theme-styles'), false );
    wp_enqueue_style('priceoflife');
});

/* ---------------------------------------------------------------------- */
/*	Add Image Sizes
/* ---------------------------------------------------------------------- */
add_image_size( 'aspect_ratio_5:2_large', 940, 380, true );
add_image_size( 'aspect_ratio_16:9_large', 940, 528, true );
add_image_size( 'aspect_ratio_3:2_large', 940, 626, true );
add_image_size( 'aspect_ratio_5:2_medium', 680, 272, true );
add_image_size( 'aspect_ratio_16:9_medium', 680, 382, true );
add_image_size( 'aspect_ratio_3:2_medium', 680, 453, true );
add_image_size( 'single_page_feature_image', 680, 0, true );
add_image_size( 'main_slider_size_large', 568, 380, true );
add_image_size( 'post_carousel', 220, 140, true );
add_filter( 'attachment_fields_to_edit', function( $fields, $post ) {
    global $_wp_additional_image_sizes;
    if ( (!isset($_wp_additional_image_sizes) || !count($_wp_additional_image_sizes)) || !isset($fields['image-size']['html']) || substr($post->post_mime_type, 0, 5) != 'image' )
        return $fields;

    $new_image_sizes = '';
    foreach ( array_keys($_wp_additional_image_sizes) as $size ) {
        if(!in_array($size, array(
            'main_slider_size_large',
        ))) continue;
        $downsize = image_downsize( $post->ID, $size );
        $enabled = $downsize[3];
        $css_id = "image-size-{$size}-{$post->ID}";
        $label = ucwords(str_replace(array('-','_'),' ',$size));
        $new_image_sizes .= "<div class='image-size-item'>\n";
        $new_image_sizes .= "\t<input type='radio' " . disabled( $enabled, false, false ) . "name='attachments[{$post->ID}][image-size]' id='{$css_id}' value='{$size}' />\n";
        $new_image_sizes .= "<label for='{$css_id}'>{$label}</label>\n";
        if ( $enabled )
            $new_image_sizes .= "<label for='{$css_id}' class='help'>" . sprintf( "(%d × %d)", $downsize[1], $downsize[2] ). "</label>\n";
        $new_image_sizes .= "</div>";
    }
    $fields['image-size']['html'] = "{$new_image_sizes}";
    return $fields;
}, 11, 2 );
add_filter( 'ss_content_thumbnail_size', function($thumbnail_size, $post){
    if(is_single())
        return 'single_page_feature_image';
    elseif(isset( $GLOBALS['post-carousel'] ))
        return 'post_carousel';
    else
        return 'aspect_ratio_5:2_medium';
}, 10, 2);
add_filter('ss_framework_posted_on_date', function($date){
    if(isset( $GLOBALS['post-carousel'] ))
        return '<span class="month">'.esc_html(get_the_date('M')).'</span>'.esc_html(get_the_date('d'));
    else
        return $date;
}, 10, 2);

/* ---------------------------------------------------------------------- */
/*	Disable automatic formatting
/* ---------------------------------------------------------------------- */
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
remove_filter('the_title', 'wptexturize');

