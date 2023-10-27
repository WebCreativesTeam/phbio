<?php


class Press_Kit_Settings {

    public function __construct() {
        add_action( 'admin_menu', array($this, 'register') );
    }
    
    public function register() {


        add_menu_page(
            'Press Kit',             
            'Press Kit',                
            'manage_options',             
            'presskit-settings',        
            array($this, 'render'),       
            'dashicons-excerpt-view',    
            21                         
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
							<h1 class="page-title"> Press Kit Settings </h1>		
						</div>
					</div>
					<div class="content-edit setting-page-wrap">
						<form method="post">
							<?php self::settings($user_id); ?>
                            <div class="save-progress">
                                <div class="save-progress-contain">
                                    <div>Use the "Update" button to save your changes!</div>
                                    <input type="submit" name="submit_form" value="Submit" class="h-10 mt-0 upload-btn">
                                </div>
                            </div>
						</form>
					</div>
				</div>
			</div>
		<?php
	}

    public function settings($user_id) {
        $settings_data = [
            [
                'type' => 'text_field',
                'args' => ['limit_pkit_project', '20', true, 'Character Limit : Project / Artist', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17,6H7A1,1,0,0,0,7,8h4v9a1,1,0,0,0,2,0V8h4a1,1,0,0,0,0-2Z"></path></svg>', 'manage_options', false, $user_id]
            ],
            [
                'type' => 'text_field',
                'args' => ['limit_pkit_username', '20', true, 'Username', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17,6H7A1,1,0,0,0,7,8h4v9a1,1,0,0,0,2,0V8h4a1,1,0,0,0,0-2Z"></path></svg>', 'manage_options', false, $user_id]
            ],
            [
                'type' => 'text_field',
                'args' => ['default_pkit_template', '10', false, 'Default Template ID', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19,2H9A3,3,0,0,0,6,5V6H5A3,3,0,0,0,2,9V19a3,3,0,0,0,3,3H15a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2ZM16,19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H16Zm0-9H4V9A1,1,0,0,1,5,8H15a1,1,0,0,1,1,1Zm4,5a1,1,0,0,1-1,1H18V9a3,3,0,0,0-.18-1H20Zm0-9H8V5A1,1,0,0,1,9,4H19a1,1,0,0,1,1,1Z"></path></svg>', 'manage_options', false, $user_id]
            ],
            [
                'type' => 'text_field',
                'args' => ['private_pkit_redirection', 'https://', false, 'Private Redirection', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11,12H3a1,1,0,0,0-1,1v8a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V13A1,1,0,0,0,11,12Zm-1,8H4V14h6ZM21.92,2.62a1,1,0,0,0-.54-.54A1,1,0,0,0,21,2H15a1,1,0,0,0,0,2h3.59l-5.3,5.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L20,5.41V9a1,1,0,0,0,2,0V3A1,1,0,0,0,21.92,2.62Z"></path></svg>', 'manage_options', false, $user_id]
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

// Instantiate the class
new Press_Kit_Settings();