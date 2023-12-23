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
        $mode = $settings['mode'];
        $test_user = $settings['test_user'];
        $test_lang = $settings['test_lang'];
        $block_key = $settings['block_key'];

        // Implement the logic to render the shortcode based on the settings
        echo do_shortcode("[pkit_block_loader test_user='{$test_user}' test_lang='{$test_lang}' block_key='{$block_key}' mode='{$mode}']");
    }

}