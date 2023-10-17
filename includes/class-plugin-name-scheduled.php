<?php


class Plugin_Name_Scheduled {

    // Data to delete after, days
    // IMPORTANT KEEP THIS SAME WITH THE UTILITY CLASS'S VALUE 
    private $KEEP_FOR = 2;


    public function __construct() {
        $this->hooks();
        $this->schedule_cleanup();
    }

    public function hooks() {
        add_action('clean_up_backup_links', array($this, 'scheduled_cleanup_backup_meta'));
    }

    public function schedule_cleanup() {
        if (!wp_next_scheduled('clean_up_backup_links')) {
            wp_schedule_event(current_time('timestamp'), 'daily', 'clean_up_backup_links');
        }
    }

    public function scheduled_cleanup_backup_meta() {
        $users = get_users([
            'role' => 'lite-version',
            'meta_key' => '_backup_date',
        ]);

        foreach ($users as $user) {
            $backup_date = get_user_meta($user->ID, '_backup_date', true);
            $days_elapsed = (current_time('timestamp') - strtotime($backup_date)) / DAY_IN_SECONDS;
            if ($days_elapsed > $this->KEEP_FOR) {
                delete_user_meta($user->ID, '_backup_meta_field');
                delete_user_meta($user->ID, '_backup_date');
            }
        }
    }

}
