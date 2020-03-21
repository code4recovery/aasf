<?php

add_action('wp_dashboard_setup',
    function () {
        global $wp_meta_boxes;

        //only admins
        if (!current_user_can('administrator')) {
            return;
        }

        $current_user = wp_get_current_user();

        //define our widget
        $widget = 'aasfmarin';
        wp_add_dashboard_widget($widget, 'Hello ' . $current_user->user_firstname, function () use ($widget) {
            echo '<p>As an administrator, you’re responsible for the health of this website. Please help by documenting any users you add or plugins you install.</p>';

            echo '<a href="https://docs.google.com/document/d/19ML-vdF4xt0uDsm8ZauyUg-1UpCJmWtzJ-CFS-PNqPw/edit#" class="button button-large" target="_blank">
              <span class="dashicons dashicons-media-text" style="margin:5px 2px 0 0"></span>
              IFAA Website Admin Guide</a>';

            echo '<style type="text/css">
            #' . $widget . ' h2 { background-color: #23282d; color: #fff; }
            #' . $widget . ' .handlediv { display: none; }
            </style>';
        });

        //move our widget to the front
        $wp_meta_boxes['dashboard']['normal']['core'] = array(
            $widget => $wp_meta_boxes['dashboard']['normal']['core'][$widget],
        ) + $wp_meta_boxes['dashboard']['normal']['core'];

    }
);
