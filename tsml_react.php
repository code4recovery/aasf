<?php

/*
takes data from https://github.com/code4recovery/airtable-json
and sends it to https://github.com/code4recovery/tsml-ui
*/

add_shortcode('tsml_react', function() {
    wp_enqueue_script('tsml_react', 'https://react.meetingguide.org/app.js');
    wp_localize_script('tsml_react', 'tsml_react_config', [
        'conference_providers' => [
            'drydocksf.org' => 'Custom',
            'MillValleyCabin.com' => 'Custom',
            'sites.google.com' => 'Custom',
            'tinyurl.com' => 'Custom',
        ],
        'strings' => [
            'en' => [
                'region' => 'City / Neighborhood',
            ],
        ]
    ]);
    return '<div id="tsml-ui"
        data-mapbox="pk.eyJ1IjoiYWFzZi1tcGFsIiwiYSI6ImNqaHl6NHY0NzByemszcHBkcHpib296OHkifQ.F7GfpF1jRosyQ0PGNUfDkA"
        data-src="https://airtable-json.aasfmarin.org"
        data-timezone="' . get_option('timezone_string') . '"
        ></div>';
});