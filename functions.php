<?php

//schedule changes loader and page
include 'schedule-changes.php';
include 'dashboard.php';
include 'remote-meetings-export.php';

//load parent style
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
});

if (function_exists('tsml_custom_types')) {
    tsml_custom_types(array(
        'BE' => 'Beginner',
        'H' => 'Chip Meeting',
        'BA' => 'Childcare',
        'EN' => 'English-speaking',
        'ES' => 'En Español',
    ));
}

//by default, tsml should show meetings in a 1 mile radius
$tsml_defaults['distance'] = 1;

//remove sidebar
add_action('widgets_init', function () {
    unregister_sidebar('sidebar-1');

    register_sidebar(array(
        'name' => 'Home Row Two',
        'id' => 'home-2',
        'description' => 'Widgets for the second row on the home page with two slots',
        'before_widget' => '<div id="%1$s" class="column %2$s">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3>',
        'after_title' => '</h3><div class="widget-content">',
    ));

    register_sidebar(array(
        'name' => 'Home Row Three',
        'id' => 'home-3',
        'description' => 'Widgets for the third row on the home page with three slots',
        'before_widget' => '<div id="%1$s" class="column %2$s">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3>',
        'after_title' => '</h3><div class="widget-content">',
    ));

    register_sidebar(array(
        'name' => 'Service Sidebar',
        'id' => 'content-service',
        'description' => 'Additional sidebar that appears on the right of the Service section pages.',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h1 class="widget-title">',
        'after_title' => '</h1>',
    ));

}, 11);

//random petaluma meeting wreaking havoc with google
tsml_custom_addresses([
    '15 Park Ave., Inverness, CA, 94937, USA' => [
        'formatted_address' => '15 Park Ave, Inverness, CA 94937, USA',
        'city' => 'Inverness',
        'postal_code' => '94937',
        'latitude' => 38.097210,
        'longitude' => -122.853340,
    ]
]);

/**
 * Register all shortcodes
 *
 * @return null
 */
function register_shortcodes() {
    add_shortcode( 'remote_meeting', 'shortcode_remote_meeting' );
}
add_action( 'init', 'register_shortcodes' );

/**
 * Hello Everyone Remote Meeting Shortcode
 *
 * @param Array $atts
 *
 * @return string
 */
function shortcode_remote_meeting($atts)
{
    global $wp_query, $post;

    ob_start();

    $atts = shortcode_atts(array(
        'day' => 'monday',
        'type' => 'post'
    ), $atts);

    $loop = new WP_Query(array(
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post_type' => $atts['type'],
        'meta_key' => $atts['day'],
        'meta_type' => 'DATE'
    ));

    $events = array();

    if ($loop->have_posts()) {

        while ($loop->have_posts()) {
            $loop->the_post();

            $meeting_format_specifics_arm = get_post_meta(get_the_ID(), 'meeting_format_specifics_arm', true);
            if (!empty($meeting_format_specifics_arm)) {
                $meeting_format_specifics_arm_value = $meeting_format_specifics_arm;
            }

            $link_to_meeting_arm = get_post_meta(get_the_ID(), 'link_to_meeting_arm', true);
            if (!empty($link_to_meeting_arm)) {
                $link_to_meeting_arm_value = $link_to_meeting_arm;
            }

            // taxonomy
            $term_obj_list = get_the_terms($post->ID, 'designation');
            $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));

            // origin
            $term_origin = get_the_terms($post->ID, 'origin');
            $origin_string = join(', ', wp_list_pluck($term_origin, 'name'));

            $day = get_post_meta(get_the_ID(), $atts['day'], true);
            if (!empty($day)) {
            	$events[] = array(
                    'day' => $atts['day'],
                    'day_value' => strtolower($day),
                    'permalink' => get_the_permalink(),
                    'title' => get_the_title(),
                    'terms_string' => $terms_string,
                    'meeting_format_specifics_arm_value' => $meeting_format_specifics_arm_value,
                    'origin_string' => $origin_string,
                    'link_to_meeting_arm_value' => $link_to_meeting_arm_value,
                );
			}
        }

    }


    if (count($events) > 0) {
		usort($events, function ($a, $b) {
            return strtotime($a['day_value']) > strtotime($b['day_value']);
        });
		
        foreach ($events as $event) {
            echo '<div id="arm-listing" style="margin:15px 0;">';
            echo '<div id="arm-time">' . $event['day_value'] . '</div>';
            echo '<div id="arm-name"><a href="' . $event['permalink'] . '">' . $event['title'] . ' </a></div>';
         		
         		if( ! empty( $event['terms_string']) ) {
		echo '<div id="arm-designation">' . $event['terms_string'] . '</div>';;
	}
	
	 //		if( ! empty( $event['meeting_format_specifics_arm_value']) ) {
	//	 echo '<div id="arm-format-spec">' . $event['meeting_format_specifics_arm_value'] . '</div>';
//	}
	
	//echo '<div id="arm-format-spec">' . $event['meeting_format_specifics_arm_value'] . '</div>';
	
		if( ! empty( $event['origin_string']) ) {
		echo '<div id="arm-origin">Origin: ' . $event['origin_string'] . '</div>';
	}

    //        echo '<div id="arm-link"><label>Link to Meeting:</label> <a href="' . $event['link_to_meeting_arm_value'] . '">' . $event['link_to_meeting_arm_value'] . '</a></div>';
            echo '</div>';
        }
    }

    wp_reset_postdata();
    return ob_get_clean();
}