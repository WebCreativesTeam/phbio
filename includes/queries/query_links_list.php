<?php

class Plugin_Query_Links_List {
    public function query($query) {
        if (!is_user_logged_in()) return;

        $current_user_id = get_current_user_id();
        $value = get_user_meta($current_user_id, 'link_list', true);

        $decodedString = urldecode($value);
        $linksArray = json_decode($decodedString, true);
        $reIndexedArray = array_values(is_array($linksArray) ? $linksArray : []);

        // Set the query to fetch no posts
        $query->set('post__in', [0]);

        // Manually override the posts property with your custom array
        $query->set('posts_per_page', count($reIndexedArray));
        $query->posts = $reIndexedArray;
        $query->post_count = count($reIndexedArray);
    }
}
