<?php

class Elementor_Block_Loader_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Block Loader widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'block_loader';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Block Loader widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'PressKit Block Loader', 'elementor-block-picker-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-code';
	}

	
	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'block', 'presskit'];
	}

	protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'elementor-block-loader-widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mode',
            [
                'label' => esc_html__( 'Mode', 'elementor-block-loader-widget' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Dev (No Mode)', 'elementor-block-loader-widget' ),
                    'prod' => esc_html__( 'Prod', 'elementor-block-loader-widget' ),
                    'help' => esc_html__( 'Help', 'elementor-block-loader-widget' ),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'test_user',
            [
                'label' => esc_html__( 'Test User', 'elementor-block-loader-widget' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => get_current_user_id(),
                'description' => 'User ID for testing purpose',
            ]
        );

        $this->add_control(
            'test_lang',
            [
                'label' => esc_html__( 'Test Language', 'elementor-block-loader-widget' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'en',
                'description' => 'Language for testing purpose',
            ]
        );

        $block_options = [];
        $blocks = Plugin_Name_Utilities::get_pkit_blocks();
        foreach ($blocks as $block) {
            $block_options[$block['key']] = $block['name'];
        }

        $this->add_control(
            'block_key',
            [
                'label' => esc_html__( 'Block Key', 'elementor-block-loader-widget' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $block_options,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo $this->render_block_loader($settings);
    }

    private function render_block_loader($settings) {
        ob_start();

        $current_user = wp_get_current_user();
        $is_admin = in_array('administrator', $current_user->roles);

        global $post;
        $is_child_page = is_a($post, 'WP_Post') && $post->post_parent;
        $is_hb_user_pkit_page = $is_child_page && get_post_type($post->post_parent) === 'hb-user-pkit';

        if (!$is_admin && $is_hb_user_pkit_page) {
            $lang = $post->post_name;
            $parent_post_id = wp_get_post_parent_id($post->ID);
            $test_user = get_post_meta($parent_post_id, 'associated_pkit_user', true) ?: $settings['test_user'];
            $data = Plugin_Name_Utilities::get_pkit_data($test_user, $lang);

            $filtered_data = array_filter($data, function($block) use ($settings, $lang) {
                return $block['block_name'] === $lang . '_' . $settings['block_key'];
            });

            foreach ($filtered_data as &$block) {
                $block['fields'] = array_filter($block['fields'], function($field) {
                    return !empty($field[1]);
                });
            }

            if($settings['mode'] === 'prod') {
                $this->render_block_html($filtered_data);
            } else {
                echo "<pre>";
                print_r($filtered_data);
                echo "</pre>";
            }
        } elseif ($is_admin && !$is_child_page && $settings['mode'] === 'help') {
            echo "<pre>";
            print_r(Plugin_Name_Utilities::get_pkit_blocks());
            echo "</pre>";
        } elseif ($is_admin && !$is_child_page && !empty($settings['block_key'])) {
            $data = Plugin_Name_Utilities::get_pkit_data($settings['test_user'], $settings['test_lang']);
            $selected_block = $this->find_selected_block($data, $settings);

            if ($selected_block) {
                if($settings['mode'] === 'prod') {
                    $this->render_block_html([$selected_block]);
                } else {
                    echo "<pre>";
                    print_r($selected_block);
                    echo "</pre>";
                }
            }
        }

        return ob_get_clean();
    }

    private function find_selected_block($data, $settings) {
        foreach ($data as &$block) {
            if ($block['block_name'] === $settings['test_lang'] . '_' . $settings['block_key']) {
                foreach ($block['fields'] as &$field) {
                    $field[1] = "Value";
                }
                return $block;
            }
        }
        return null;
    }

    private function render_block_html($blocks) {
        foreach ($blocks as $block) {
            echo "<div class='pkit_blocks'>";
            foreach ($block['fields'] as $field) {
                echo "<div class='pkit_block'>";
                echo "<label>" . htmlspecialchars($field[0]) . "</label> ";
                echo "<input type='text' value='" . htmlspecialchars($field[1]) . "' disabled>";
                echo "</div>";
            }
            echo "</div>";
        }
    }


}