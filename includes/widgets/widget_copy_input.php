<?php
class Elementor_Custom_Input_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'custom_input_widget';
    }

    public function get_title() {
        return 'Custom Input';
    }

    public function get_icon() {
        return 'fa fa-pencil';  // Choose your desired icon
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        // You can add controls like text fields, select fields, etc. here if needed.
    }

    protected function render() {
        $user_id = get_current_user_id();
        $username = get_user_meta($user_id, 'username', true);
        $site_url = get_site_url();
        $full_link = $site_url . '/' . $username;
        ?>
        <div class="custom-input-widget">
            <input type="text" readonly value="<?php echo esc_attr($full_link); ?>">
            <button onclick="copyToClipboard(this.previousElementSibling)">Copy</button>
        </div>
        <script>
            function copyToClipboard(elem) {
                elem.select();
                document.execCommand("copy");
            }
        </script>
        <?php
    }
}
