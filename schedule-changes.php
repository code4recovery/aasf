<?php

//code to handle loading and displaying remote data

class ScheduleChanges
{

    //fetch data from remote, save to wp_options
    public static function fetchData()
    {
        //fetch JSON string response from server
        if (!$response = wp_remote_retrieve_body(wp_remote_get('https://aasf.co/aasfmarin_changed_meetings_json.cfm', array(
            'timeout' => 60,
        )))) {
            die('no response');
        }

        //convert to array
        if (!$changes = json_decode($response)) {
            die('invalid response');
        }

        //trim array properties
        $changes = array_map(function ($change) {
            foreach ($change as $key => $value) {
                $change->{$key} = trim($value);
                if ($key == 'time' && $value == '11:59 PM') {
                    $change->time = 'Midnight';
                }

            }
            return $change;
        }, $changes);

        //define a structure for the data
        $framework = array(
            'SF' => array(
                'New' => array(),
                'Revisions' => array(),
                'Discontinued' => array(),
            ),
            'Marin' => array(
                'New' => array(),
                'Revisions' => array(),
                'Discontinued' => array(),
            ),
        );

        //attach data to structure
        foreach ($changes as $change) {
            if (array_key_exists($change->region, $framework) && array_key_exists($change->category, $framework[$change->region])) {
                $framework[$change->region][$change->category][] = $change;
            } else {
                dd($change);
            }
        }

        //persist to options table
        update_option('aasf_schedule_changes', $framework, false);

        dd($framework);
    }

    public static function displayData()
    {
        //define strings
        $strings = array(
            'SF' => 'San Francisco',
            'Marin' => 'Marin',
            'New' => 'New Meetings',
            'Revisions' => 'Meetings Recently Changed',
            'Discontinued' => 'No Longer Meeting',
        );

        //load from database
        $framework = get_option('aasf_schedule_changes');

        //start output
        $output = '';

        //append to output
        foreach ($framework as $region => $categories) {
            foreach ($categories as $category => $changes) {
                if (!count($changes)) {
                    continue;
                }

                $output .= '<h3>' . $strings[$region] . ' - ' . $strings[$category] . '</h3>
				<table>
					<thead>
						<tr>
							<th>Day</th>
							<th>Time</th>
							<th>Area</th>
							<th>Meeting Name</th>
							<th>Location</th>
							<th>Details</th>
						</tr>
					</thead>
					<tbody>';
                foreach ($changes as $change) {
                    $output .= '<tr>
						<td>' . $change->day . '</td>
						<td>' . $change->time . '</td>
						<td>' . $change->neighborhood . '</td>
						<td>' . $change->meeting_name . '</td>
						<td>' . $change->address . '<br>' . $change->city . '</td>
						<td><strong>' . $change->designations . '</strong><br>' . $change->revision_note . '</td>
					</tr>';
                }
                $output .= '</tbody>
				</table>';
            }
        }

        return $output;

    }

}

//debug
//ScheduleChanges::fetchData();

//run import every hour
add_action('aasf_schedule_changes', function () {
    ScheduleChanges::fetchData();
});

if (!wp_next_scheduled('aasf_schedule_changes')) {
    wp_schedule_event(time(), 'hourly', 'aasf_schedule_changes');
}

//display via shortcode
add_shortcode('schedule-changes', array('ScheduleChanges', 'displayData'));

//cron
