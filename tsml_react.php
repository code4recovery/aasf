<?php

/*
takes data from https://github.com/code4recovery/airtable-json
and sends it to https://github.com/code4recovery/tsml-ui
*/

add_shortcode('tsml_react', function() {
    $mapbox_key = 'pk.eyJ1IjoiYWFzZi1tcGFsIiwiYSI6ImNqaHl6NHY0NzByemszcHBkcHpib296OHkifQ.F7GfpF1jRosyQ0PGNUfDkA';
    $conference_providers = [
        'sites.google.com' => 'Custom',
        'tinyurl.com' => 'Custom',
        'MillValleyCabin.com' => 'Custom',
        'drydocksf.org' => 'Custom',
    ];
    $host = 'https://react.meetingguide.org/app.js';
    $url = 'https://airtable-json.aasfmarin.org';
    wp_enqueue_script('tsml_react', $host);
    wp_localize_script('tsml_react', 'tsml_react_config', array(
        'timezone' => get_option('timezone_string'),
        'conference_providers' => $conference_providers,
        'show' => array(
            'cityAsRegionFallback' => false,
        ),
        'strings' => array(
            'en' => array(
                'region' => 'City / Neighborhood',
            ),
        )
    ));
    return '<meetings src="' . $url . '" mapbox="' . $mapbox_key . '"/>';
});