<?php

class Plugin_Name_Dashboard {
    public function __construct() {
        add_action( 'admin_menu', array($this, 'register') );
    }
    
    public function register() {
        add_menu_page(
			'Link in Bio Settings',             
			'Link in Bio Settings',                
			'manage_options',             
			'linkin-bio-settings',        
			array($this, 'render'),       
			'dashicons-admin-generic',    
			100                           
		);
    }
    
    public function render() {
		$user_id = get_current_user_id();
		?>
			<div class="dashboard-layout">
				<div class="main-area">
					<div class="actions-area">
					    <div class="title-area">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"></path></svg>
							<h1 class="page-title"> Link In Bio Settings </h1>		
						</div>
					</div>
					<div class="content-edit">
						<form method="post">
							<?php self::settings(); ?>
							<input type="submit" name="submit_form" value="Submit" class="upload-btn">
						</form>
					</div>
				</div>
			</div>
		<?php
	}

    public function settings() {
        $settings_data = [
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'limit_project',
                    'default' => '20',
                    'is_bool' => true,
                    'label' => 'Character Limit : Project / Artist',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ],
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'limit_username',
                    'default' => '20',
                    'is_bool' => true,
                    'label' => 'Username',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ],
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'limit_bio',
                    'default' => '150',
                    'is_bool' => true,
                    'label' => 'Character Limit : Bio',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ],
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'limit_links_lite',
                    'default' => '5',
                    'is_bool' => true,
                    'label' => 'Links Limit : Lite Version',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ],
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'limit_links_full',
                    'default' => '10',
                    'is_bool' => true,
                    'label' => 'Links Limit : Full Version',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ],
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'default_template',
                    'default' => '10',
                    'is_bool' => false,
                    'label' => 'Default Template ID',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ],
            [
                'type' => 'text_field',
                'args' => [
                    'key' => 'private_redirection',
                    'default' => 'https://',
                    'is_bool' => false,
                    'label' => 'Private Redirection',
                    'icon' => '<svg ... ></svg>',
                    'capability' => 'manage_options',
                    'auto_echo' => false,
                    'user_id' => $user_id
                ]
            ]
        ];
    
        foreach ($settings_data as $setting) {
            $method = $setting['type']; // e.g., 'text_field'
            
            // Check if the method exists
            if (method_exists('Plugin_Name_Builder', $method)) {
                call_user_func_array(['Plugin_Name_Builder', $method], $setting['args']);
            }
        }
    }
    
    
}