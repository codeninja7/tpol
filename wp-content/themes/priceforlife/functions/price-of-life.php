<?php

add_action('wp_enqueue_scripts', function(){
    wp_register_style('priceoflife', SS_BASE_URL . 'css/priceoflife.css', array('ss-theme-styles'), false );
    wp_enqueue_style('priceoflife');
});