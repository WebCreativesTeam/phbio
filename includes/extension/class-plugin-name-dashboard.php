<?php


class Press_Kit_Dashboard {

    public function __construct() {
        add_action( 'admin_menu', array($this, 'register') );
    }
    
    public function register() {
        add_menu_page(
            'Press Kit',             
            'Press Kit',                
            'manage_options',             
            'press-kit-settings',        
            array($this, 'render'),       
            'dashicons-excerpt-view',    
            21                         
        );
    }

    public function render() {
        echo "Hi";
    }
    
}

// Instantiate the class
new Press_Kit_Dashboard();