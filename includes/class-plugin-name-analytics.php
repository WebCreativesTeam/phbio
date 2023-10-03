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
    // public static function get_top_performing_link($user_id) {
    //     global $wpdb;
    
    //     // First, get the top-performing link based on clicks
    //     $top_link = $wpdb->get_row(
    //         $wpdb->prepare(
    //             "SELECT link, COUNT(*) as clicks
    //              FROM " . self::$table_name . " 
    //              WHERE user_id = %d 
    //              GROUP BY link 
    //              ORDER BY clicks DESC 
    //              LIMIT 1",
    //             $user_id
    //         ),
    //         ARRAY_A  // This argument ensures the result is returned as an associative array
    //     );
    
    //     if (!$top_link) {
    //         return null; // No link found
    //     }
    
    //     // Next, fetch all timestamps for that link
    //     $timestamps = $wpdb->get_col(
    //         $wpdb->prepare(
    //             "SELECT clicked_at 
    //              FROM " . self::$table_name . " 
    //              WHERE user_id = %d AND link = %s 
    //              ORDER BY clicked_at DESC",
    //             $user_id, $top_link['link']
    //         )
    //     );
    
    //     // Add the timestamps to the result array
    //     $top_link['timestamps'] = $timestamps;
    
    //     return $top_link;
    // }


    public static function get_top_performing_links($user_id, $limit = 3, $start_date = null, $end_date = null) {
        echo $start_date;
        echo $end_date;
        global $wpdb;
        
        // Create the base SQL query
        $sql = "SELECT link
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
        
        // Prepare the SQL query
        if ($start_date && $end_date) {
            // If the start and end dates are provided, include them in the prepare method
            $top_links = $wpdb->get_results($wpdb->prepare($sql, $user_id, $start_date, $end_date, $limit), ARRAY_A);

             // Increment the end_date by one day
            $end_date_dt = new DateTime($end_date);
            $end_date_dt->modify('+1 day');
            $end_date = $end_date_dt->format('Y-m-d');
        } else {
            // If the start and end dates are NOT provided, exclude them from the prepare method
            $top_links = $wpdb->get_results($wpdb->prepare($sql, $user_id, $limit), ARRAY_A);
        }
        
        if (!$top_links) {
            return null; // No links found
        }
        
        // Extract link names from the associative arrays
        $link_names = array_map(function($link_info) {
            return $link_info['link'];
        }, $top_links);
        
        return $link_names;
    }
    
    
    public static function get_total_views_for_page($page_link, $start_date = null, $end_date = null) {
        echo $start_date;
        echo $end_date;
        global $wpdb;
    
        // Create the base SQL query
        $sql = "SELECT COUNT(*) 
                FROM {$wpdb->prefix}page_views 
                WHERE page_link = %s";
    
        // If the start and end dates are provided, add them to the SQL query
        if ($start_date && $end_date) {
            $sql .= " AND viewed_at >= %s AND viewed_at <= %s";
             // Increment the end_date by one day
            $end_date_dt = new DateTime($end_date);
            $end_date_dt->modify('+1 day');
            $end_date = $end_date_dt->format('Y-m-d');
        }
    
        // Prepare the SQL query
        if ($start_date && $end_date) {
            // If the start and end dates are provided, include them in the prepare method
            $views = $wpdb->get_var($wpdb->prepare($sql, $page_link, $start_date, $end_date));
        } else {
            // If the start and end dates are NOT provided, exclude them from the prepare method
            $views = $wpdb->get_var($wpdb->prepare($sql, $page_link));
        }
    
        return (string)$views;
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

