<?php
/**
 * Plugin Name: WordPress Leiki API
 * Plugin URI: http://plug.direct
 * Description: A WordPress plugin that implements the Leiki Smart Profiles API to parse a WordPress blog post text and extract the IAB Tier 2 tags and Focus100K Profile that Leiki generates. Then, Use those API response data microdata format on post head tag.
 * Author: Juyal Ahmed
 * Author URI: https://www.codeatomic.com
 * Version: 1.0
 */
/*error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);*/

require_once( 'titan-framework-checker.php' );
require_once( 'titan-post-options.php' );
require_once( 'functions.php' );

add_action( 'admin_enqueue_scripts', 'wordpress_leiki_api_scripts' );
function wordpress_leiki_api_scripts( $hook ) {
    if ( 'edit.php' === $hook || 'post-new.php' === $hook || 'post.php' === $hook ) {
        wp_enqueue_script( 'custom-wordpress-leiki-api', plugins_url( 'js/custom.js?v=1.1', __FILE__ ), array('jquery') );
        wp_localize_script( 'custom-wordpress-leiki-api', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

add_action('wp_head','wordpress_leiki_api_microdata');
function wordpress_leiki_api_microdata() {
    global $post;

    $focus100k = get_post_meta($post->ID, 'wp-leiki-api_leiki_preview_content_focus100k', true);
    $focus100k = json_decode($focus100k);
    $category = [];
    if ( !empty($focus100k) && !empty($focus100k->data) && !empty($focus100k->data->items) ) {
        foreach ($focus100k->data->items as $k => $v) {
            $category[] = $v->title;
        }
    }

    $iabtier2 = get_post_meta($post->ID, 'wp-leiki-api_leiki_preview_content_iabtier2', true);
    $iabtier2 = json_decode($iabtier2);
    $keywords = [];
    if ( !empty($iabtier2) && !empty($iabtier2->data) && !empty($iabtier2->data->items) ) {
        foreach ($iabtier2->data->items as $k => $v) {
            $keywords[] = $v->title;
        }
    }

    $output = "\n\n<!-- Start of Leiki Microdata -->\n";
    $output.= '<meta class="swiftype" name="author" content="'. get_the_author_meta('nicename', $post->post_author) .'">' . "\n";
    $output.= '<meta class="swiftype" name="creator" content="'. get_the_author_meta('nicename', $post->post_author) .'">' . "\n";
    $output.= '<meta class="swiftype" name="dateCreated" content="'. $post->post_date .'">' . "\n";
    $output.= '<meta class="swiftype" name="dateModified" content="'. $post->post_modified .'">' . "\n";
    $output.= '<meta class="swiftype" name="datePublished" content="'. $post->post_date .'">' . "\n";
    $output.= '<meta class="swiftype" name="discussionUrl" content="'. get_comments_link( $post->ID ) .'">' . "\n";
    $output.= '<meta class="swiftype" name="url" content="'. get_the_permalink( $post->ID ) .'">' . "\n";
    $output.= '<meta class="swiftype" name="name" content="'. get_the_title( $post->ID ) .'">' . "\n";
    $output.= '<meta class="swiftype" name="headline" content="'. get_the_title( $post->ID ) .'">' . "\n";
    $output.= '<meta class="swiftype" name="category" data-type="string" content="' . implode(', ', $category) .'">' . "\n";
    $output.= '<meta class="swiftype" name="keywords" data-type="string" content="' . implode(', ', $keywords) .'">' . "\n";
    if ( has_post_thumbnail($post) ) {
        $output .= '<meta class="swiftype" name="thumbnailUrl" data-type="string" content="' . get_the_post_thumbnail_url( $post, 'thumbnail' ) . '">' . "\n";
        $output .= '<meta class="swiftype" name="image" data-type="string" content="' . get_the_post_thumbnail_url( $post, 'full' ) . '">' . "\n";
    }
    $output.= "<!-- End of Leiki Microdata -->\n\n";
    echo $output;
}

add_action( 'wp_ajax_wp_leiki_api_action', 'wp_leiki_api_action' );
function wp_leiki_api_action() {
    $titan = TitanFramework::getInstance( 'wp-leiki-api' );
    $api_key = $titan->getOption( "api_key" );

    if ( $api_key ) {
        $post_url = $_POST['post_url'];
        $method = $_POST['method'];
        $api_url = 'https://analysis-trial.leiki.com/focus/api?method=analyse&classification=' . $method . '&apikey='.$api_key.'&format=json&target=' . urlencode($post_url);

        $output = get_curl_content($api_url);
        echo json_encode(json_decode($output));
    } else {
        $return = ['error'=> true, 'message'=> 'Leiki API key not found, please visit Settings > Leiki API'];
        echo json_encode($return);
    }

    wp_die(); // this is required to terminate immediately and return a proper response
}

