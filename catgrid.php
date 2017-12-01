<?php
/*
Plugin Name: List Category Links with Pictures
Description: Shortcode to load an unstyled list of Taxonomy Term Links, Depends WP Term Images plugin: https://wordpress.org/plugins/wp-term-images/
Author: Joe Bacal, Smith College ETS
*/

//shortcode function
function catgrid_taxonomy_grid($atts, $content = null) {

    $atts = shortcode_atts( 
        array( 
            'taxonomy' => 'category' 
        ), 
    $atts );

    $tax =  $atts['taxonomy'];

    $term_objs = get_terms([
        'taxonomy' => $tax,
        'hide_empty' => false
    ]);

    //prepare an array of catgrido keyed arrays
    $catgridos = [];
    foreach($term_objs as $obj){
        $catgrido = [];
        $catgrido['img_id'] = get_term_meta($obj->term_id)['image'][0];
        $catgrido['img_data'] = wp_get_attachment_image_src($catgrido['img_id'], 'full');
        $catgrido['img_url'] = $catgrido['img_data'][0];
        $catgrido['orig_img_width'] = $catgrido['img_data'][1];
        $catgrido['term_id'] = $obj->term_id;
        $catgrido['term_link'] = get_term_link($obj);
        $catgrido['name'] = $obj->name;
        $catgrido['desc'] = $obj->description;

        array_push($catgridos, $catgrido);
    }

    //output to page
    foreach ($catgridos as $cat) {
        //$style_string = 'background-image:url("' . esc_url($cat['img_url']) . '")';
        //print_r($style_string);
        echo '<article class="catgrid-article ' . $tax . '"><a style="text-decoration:none;"  href="'. $cat['term_link'] .'">';
            echo '<div class="catgrid-img-wrap">';
                echo '<img class="catgrid-img" src="' . $cat['img_url'] . '">';
            echo '</div>';
            echo '<div class="catgrid-cat-title">';
                echo $cat['name'];
            echo '</div>';
            echo '<div class="catgrid-cat-desc">';
                echo $cat['desc'];
            echo '</div>';
        echo '</a></article>';
    }      
}
    
//register it
add_shortcode('taxlist', 'catgrid_taxonomy_grid');
    

