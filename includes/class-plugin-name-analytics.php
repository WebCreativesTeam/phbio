<?php


class Plugin_Name_Analytics {

    private static $table_name;
    private static $social_table_name;

    public function __construct() {
        global $wpdb;
        self::$table_name = $wpdb->prefix . 'link_clicks';
        self::$social_table_name = $wpdb->prefix . 'social_link_clicks';
    }

    /**
     * Get the top-performing link for a given user ID.
     *
     * @param int $user_id
     * @return array|null Link data or null if no data found.
     */
   


    public static function get_top_performing_links($user_id, $limit = 999, $start_date = null, $end_date = null, $clicks = false) {

        // Increment the end_date by one day
        if ($end_date) {
            $end_date_dt = new DateTime($end_date);
            $end_date_dt->modify('+1 day');
            $end_date = $end_date_dt->format('Y-m-d');
        }
    
        global $wpdb;
    
        // Create the base SQL query
        $sql = "SELECT link, COUNT(*) as click_count
                FROM " . self::$table_name . " 
                WHERE user_id = %d";
        
        // If the start and end dates are provided, add them to the SQL query
        if ($start_date && $end_date) {
            $sql .= " AND clicked_at >= %s AND clicked_at <= %s";
        }
        
        // Add the GROUP BY and ORDER BY clauses to the SQL query
        $sql .= " GROUP BY link 
                  ORDER BY COUNT(*) DESC 
                  LIMIT %d";
        
        // Prepare and execute the SQL query
        if ($start_date && $end_date) {
            $top_links = $wpdb->get_results($wpdb->prepare($sql, $user_id, $start_date, $end_date, $limit), ARRAY_A);
        } else {
            $top_links = $wpdb->get_results($wpdb->prepare($sql, $user_id, $limit), ARRAY_A);
        }
        
        if (!$top_links) {
            return '<p>No links found.</p>'; // No links found
        }
    
        // Start the HTML table
        $table_html = '<table border="1">';
        
        // Add table headers
        $table_html .= '<tr><th>Rank</th><th>Link</th>';
        if ($clicks) {
            $table_html .= '<th>Click Count</th>';
        }
        $table_html .= '</tr>';
    
        // Add table rows
        $rank = 1;
        foreach ($top_links as $link_info) {
            $table_html .= '<tr>';
            $table_html .= '<td>' . $rank . '</td>';
            $table_html .= '<td>' . esc_html($link_info['link']) . '</td>';
            if ($clicks) {
                $table_html .= '<td>' . intval($link_info['click_count']) . '</td>';
            }
            $table_html .= '</tr>';
            $rank++; // Increment the rank
        }
        
        // End the HTML table
        $table_html .= '</table>';
    
        return $table_html;
    }
    
    
    public static function get_total_clicks_for_link($link_id) {
        global $wpdb;
    
        // Create the SQL query
        $sql = "SELECT COUNT(*) 
                FROM " . self::$table_name . " 
                WHERE link_id = %d";
    
        // Prepare and execute the SQL query
        $total_clicks = $wpdb->get_var($wpdb->prepare($sql, $link_id));
        
        return (int)$total_clicks;
    }
    
    
    public static function get_total_views_for_page($page_link, $start_date = null, $end_date = null) {
        // Increment the end_date by one day
        if ($end_date) {
            $end_date_dt = new DateTime($end_date);
            $end_date_dt->modify('+1 day');
            $end_date = $end_date_dt->format('Y-m-d');
        }
     
        global $wpdb;
    
        // Create the base SQL query for daily views
        $sql_daily_views = "SELECT COUNT(*) as daily_views, DATE(viewed_at) as view_date
                    FROM {$wpdb->prefix}page_views 
                    WHERE page_link = %s";
        
        // If the start and end dates are provided, add them to the SQL query
        if ($start_date && $end_date) {
            $sql_daily_views .= " AND viewed_at >= %s AND viewed_at <= %s";
        }
        
        $sql_daily_views .= " GROUP BY view_date
                               ORDER BY view_date ASC"; // ASC for chronological order in line chart
        
        // Prepare and execute the SQL query
        if ($start_date && $end_date) {
            $daily_views = $wpdb->get_results($wpdb->prepare($sql_daily_views, $page_link, $start_date, $end_date), ARRAY_A);
        } else {
            $daily_views = $wpdb->get_results($wpdb->prepare($sql_daily_views, $page_link), ARRAY_A);
        }
    
        // Extracting labels and data for Chart.js
        $labels = array_map(function($entry) {
            return $entry['view_date'];
        }, $daily_views);
    
        $data = array_map(function($entry) {
            return $entry['daily_views'];
        }, $daily_views);
    
        // Returning data for Chart.js
        return array(
            'labels' => $labels,
            'data' => $data
        );
    }
    
    
    public static function calculate_ctr($page_link, $start_date = null, $end_date = null) {
        global $wpdb;
    
        // Increment the end_date by one day if it is set
        if ($end_date) {
            $end_date_dt = new DateTime($end_date);
            $end_date_dt->modify('+1 day');
            $end_date = $end_date_dt->format('Y-m-d');
        }
    
        // SQL to get total views
        $sql_views = "SELECT COUNT(*) 
                      FROM {$wpdb->prefix}page_views 
                      WHERE page_link = %s";
                      
        // SQL to get total clicks
        $sql_clicks = "SELECT COUNT(*) 
                       FROM " . self::$table_name . " 
                       WHERE link = %s";
    
        // Apply date filters if they are set
        if ($start_date && $end_date) {
            $sql_views .= " AND viewed_at >= %s AND viewed_at <= %s";
            $sql_clicks .= " AND clicked_at >= %s AND clicked_at <= %s";
            
            $total_views = $wpdb->get_var($wpdb->prepare($sql_views, $page_link, $start_date, $end_date));
            $total_clicks = $wpdb->get_var($wpdb->prepare($sql_clicks, $page_link, $start_date, $end_date));
        } else {
            $total_views = $wpdb->get_var($wpdb->prepare($sql_views, $page_link));
            $total_clicks = $wpdb->get_var($wpdb->prepare($sql_clicks, $page_link));
        }
    
        // Check if views are greater than 0 to avoid division by zero
        if($total_views > 0) {
            $ctr = ($total_clicks / $total_views) * 100;
        } else {
            $ctr = 0;
        }
    
        return array(
            'total_views' => (int)$total_views,
            'total_clicks' => (int)$total_clicks,
            'ctr' => round($ctr, 2) // rounding to two decimal places for cleaner display
        );
    }
    
    
    
    /**
     * Get the top-performing link for a given user ID.
     *
     * @param int $user_id
     * @return array|null Link data or null if no data found.
     */
    public static function get_top_performing_social_link($user_id) {
        global $wpdb;
    
        // First, get the top-performing link based on clicks
        $top_link = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT link, COUNT(*) as clicks
                 FROM " . self::$social_table_name . " 
                 WHERE user_id = %d 
                 GROUP BY link 
                 ORDER BY clicks DESC 
                 LIMIT 1",
                $user_id
            ),
            ARRAY_A  // This argument ensures the result is returned as an associative array
        );
    
        if (!$top_link) {
            return null; // No link found
        }
    
        // Next, fetch all timestamps for that link
        $timestamps = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT clicked_at 
                 FROM " . self::$social_table_name . " 
                 WHERE user_id = %d AND link = %s 
                 ORDER BY clicked_at DESC",
                $user_id, $top_link['link']
            )
        );
    
        // Add the timestamps to the result array
        $top_link['timestamps'] = $timestamps;
    
        return $top_link;
    }

    
    public static function get_all_link_data() {
        global $wpdb;
    
        $table_name = self::$table_name;
    
        // Fetch all rows from the table
        $results = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY user_id, clicked_at DESC",
            ARRAY_A
        );
    
        // Organize the results by user and link
        $organized_data = [];
        foreach ($results as $row) {
            $user_id = $row['user_id'];
            $link = $row['link'];
    
            if (!isset($organized_data[$user_id])) {
                $organized_data[$user_id] = [];
            }
    
            if (!isset($organized_data[$user_id][$link])) {
                $organized_data[$user_id][$link] = [
                    'clicks' => 0,
                    'timestamps' => []
                ];
            }
    
            $organized_data[$user_id][$link]['clicks'] += 1;
            $organized_data[$user_id][$link]['timestamps'][] = $row['clicked_at'];
        }
    
        return $organized_data;
    }
    
    public static function get_top_performing_users() {
        global $wpdb;
    
        $table_name = self::$table_name;
    
        // Aggregate the clicks by user_id and order by total clicks
        $results = $wpdb->get_results(
            "SELECT user_id, COUNT(*) as total_clicks 
             FROM $table_name 
             GROUP BY user_id 
             ORDER BY total_clicks DESC",
            ARRAY_A
        );
    
        return $results;
    }

    
    

}

