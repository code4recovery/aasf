<?php

/*
takes data from https://github.com/code4recovery/airtable-json
and sends it to https://github.com/code4recovery/react
*/

add_shortcode('tsml_react', function() {
    $mapbox_key = 'pk.eyJ1IjoiYWFzZi1tcGFsIiwiYSI6ImNqaHl6NHY0NzByemszcHBkcHpib296OHkifQ.F7GfpF1jRosyQ0PGNUfDkA';
    $conference_providers = [
        'sites.google.com' => 'Custom',
        'tinyurl.com' => 'Custom',
        'MillValleyCabin.com' => 'Custom',
        'drydocksf.org' => 'Custom',
    ];
    $is_local = substr(get_site_url(), -5) == '.test';
    $host = 'https://react.' . ($is_local ? 'test' : 'meetingguide.org');
    $url = 'https://airtable-json.' . ($is_local ? 'test' : 'aasfmarin.org');
    wp_enqueue_style('tsml_react', $host . '/style.css');
    wp_enqueue_script('tsml_react', $host . '/app.js');
    wp_localize_script('tsml_react', 'tsml_react_config', array(
        'timezone' => get_option('timezone_string'),
        'conference_providers' => $conference_providers,
        'show' => array(
            'cityAsRegionFallback' => false,
        )
    ));
    return '<meetings src="' . $url . '" mapbox="' . $mapbox_key . '"/>';
});

/*
        'BE' => 'Beginner',
        'H' => 'Chip Meeting',
        'BA' => 'Childcare',
        'EN' => 'English-speaking',
        'S' => 'En Español',
*/