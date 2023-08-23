<?php


class Plugin_Name_Analytics {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'link_clicks';
    }

    /**
     * Get the top-performing link for a given user ID.
     *
     * @param int $user_id
     * @return array|null Link data or null if no data found.
     */
    public function get_top_performing_link($user_id) {
        global $wpdb;

        // Query to get the top-performing link based on clicks
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT link, clicks FROM $this->table_name WHERE user_id = %d ORDER BY clicks DESC LIMIT 1",
                $user_id
            ),
            ARRAY_A  // This argument ensures the result is returned as an associative array
        );

        return $result;
    }

}
