<?php

add_action('wp_ajax_nopriv_remote-meetings', 'export_remote_meetings');
add_action('wp_ajax_remote-meetings', 'export_remote_meetings');

function export_remote_meetings(){
    global $wpdb;

    $columns = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday',
        'additional_access_details_arm',  'platform_arm', 'seventh_trad_link_arm', 'seventh_platform_arm', 
        'link_to_meeting_arm', 'meeting_format_specifics_arm'];

    $blank_cols = [];
    foreach ($columns as $column) {
        $blank_cols[$column] = null;
    }

    //add meeting to array keyed by id
    $meetings = [];
    $results = $wpdb->get_results('SELECT ID, post_name, post_modified, post_title, post_content 
        FROM ' . $wpdb->posts . ' p 
        WHERE post_type = "remote_meeting" 
            AND post_status = "publish"');
    foreach ($results as $result) {
        $meetings[$result->ID] = array_merge([
            'name' => $result->post_title,
            'updated' => $result->post_modified,
            'slug' => $result->post_name,
            'content' => trim(strip_tags($result->post_content)),
        ], $blank_cols);
    }

    //add meta keys
    $results = $wpdb->get_results('SELECT post_id, meta_key, meta_value 
        FROM ' . $wpdb->postmeta . ' 
        WHERE post_id IN (' . implode(',', array_keys($meetings)) . ')
            AND meta_key IN (' . implode(',', array_map(function($col) { return '"' . $col . '"'; }, $columns)) . ')');
    foreach ($results as $result) {
        if (array_key_exists($result->post_id, $meetings)) {
            $meetings[$result->post_id][$result->meta_key] = $result->meta_value;
        }
    }

    //render file
    $file = fopen('php://memory', 'w'); 
    fputcsv($file, array_merge(['name', 'updated', 'slug', 'content'], $columns), ',');
    foreach ($meetings as $meeting) { 
        fputcsv($file, $meeting, ',');
    }
    fseek($file, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="export.csv";');
    fpassthru($file);
    

}
