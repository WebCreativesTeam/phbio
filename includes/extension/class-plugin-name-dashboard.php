<?php


class Press_Kit_Dashboard {
    const ERROR_HIDE_LOGO= "<a href='/upgrade' class='text-gray-700 no-underline font-semi-bold' target='_blank'>Unlock the 'Hide Website Logo' feature instantly by <span class='text-[#F1441E] font-bold'>Going PRO</span>.</a>";
    const ERROR_TEMPLATE_MSG= "<a href='/upgrade' class='text-gray-700 no-underline font-semi-bold' target='_blank'>Unlock premium templates instantly by <span class='text-[#F1441E] font-bold'>Going PRO</span>.</a>";


    private $dynamic_tags = Array();

    
    public function __construct() {
        add_action( 'admin_menu', array($this, 'register') );
    }
    
    public function register() {


       add_submenu_page('presskit-settings', 'New Template', 'New Template', 'manage_options', 'post-new.php?post_type=pkit-template');
       add_submenu_page('presskit-settings', 'Manage Templates', 'Manage Templates', 'manage_options', 'edit.php?post_type=pkit-template');
       add_submenu_page('presskit-settings', 'User Press Kits', 'User Press Kits', 'manage_options', 'edit.php?post_type=hb-user-pkit');

        

        add_menu_page(
            'My Press Kit',             
            'My Press Kit',                
            'read',             
            'my-presskit',        
            array($this, 'render'),       
            'dashicons-excerpt-view',    
            101                          
        );

        // Get current user
        $current_user = wp_get_current_user();

        // Check if current user has the Administrator role
        if (in_array('administrator', $current_user->roles)) {
            remove_menu_page('my-presskit'); // Hide Plugins
        }

    }

   
    private function init_dynamic() {
        // Get Dynamic Tags
        $user_id = get_current_user_id(); 
    
        $template_id = Plugin_Name_Utilities::handle_user_meta('selected_template', 'read', $user_id);
        
        if($template_id === NULL || !$template_id || strlen($template_id) < 1 ) {
            $template_id = get_user_meta(1, 'default_template', true);		
        }
    
        // Get the dynamic tags from the utility function
        $dynamic_tags = Plugin_Name_Utilities::get_unique_dynamic_tag_names_from_template($template_id);
    
        // Filter the tags that start with "ph__" and remove the prefix
        $this->dynamic_tags = array_map(function($tag) {
            return str_replace('ph__', '', $tag);
        }, array_filter($dynamic_tags, function($tag) {
            return strpos($tag, 'ph__') === 0;
        }));
    
        
        return $template_id;
    }
    function render() {

        // User
        $user_id = get_current_user_id(); 

        
        $template_id__saved = $this->init_dynamic();


    
        
		if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
			$user_id = intval($_GET['user_id']); // use user_id from URL if admin
		}

        include_once plugin_dir_path(__FILE__) . '../class-plugin-name-header.php';

		?>
        
<div class="dashboard-layout">

<div x-data="dashboard" 
        x-init="() => { 
        let storedState = localStorage.getItem('alpineState');
        if (storedState) {
            let state = JSON.parse(storedState);
            editMode = state.editMode;
            activeTab = state.activeTab;
            showSettings = state.showSettings;
            showTemplates = state.showTemplates;
            activeFilter = state.activeFilter;
        }
        $watch('activeTab', () => saveState());
        $watch('showSettings', () => saveState());
        $watch('showTemplates', () => saveState());
        $watch('activeFilter', () => saveState());
    }"
     class="relative main-area"> <!-- Added relative positioning here -->

    <!--Top Actions - STARTS HERE -->
    <div class="actions-area" x-show="!showTemplates">
        <?php self::top_actions(); ?>
    </div>
    <!--Top Actions - ENDS HERE -->

    <!-- Settings Content - STARTS HERE -->
    <div x-show="showSettings" class="content-settings">
        <?php self::area__settings($user_id); ?>
    </div>
    <!-- Settings Content - ENDS HERE -->


    <!-- Templates Content Area - STARTS HERE -->
    <div x-show="showTemplates" class="static mb-32 content-templates">
        <?php self::area__templates($user_id); ?>
    </div>
    <!-- Templates Content Area - ENDS HERE -->

    <!-- Preview Mode - STARTS HERE -->
    <div x-show="editMode" class="content-preview">
        <?php self::area__preview($user_id); ?>
    </div>
    <!-- Preview Mode - ENDS HERE -->
 
    <!-- Edit Mode - STARTS HERE -->
    <div x-show="!editMode" class="content-edit">
        <?php self::area__edit($user_id); ?>
    </div>
    <!-- Edit Mode - ENDS HERE -->
    
            
       
    </div>
</div>

<?php include_once plugin_dir_path(__FILE__) . '../class-plugin-name-footer.php'; ?>


</div>



	

	
	<?php
	}
    public function top_actions() { ?>

            <h1 x-text="!editMode ? 'Edit' : 'Preview' " class="page-title"></h1>
    
            <!-- New Flex Container for Buttons and Toggle -->
            <div class="action-buttons">
    
                <!-- Button: Select Template -->
                <button @click="showTemplates = !showTemplates; showSettings = false;" class="text-sm uppercase template-btn md:px-8 md:mr-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19,2H9A3,3,0,0,0,6,5V6H5A3,3,0,0,0,2,9V19a3,3,0,0,0,3,3H15a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2ZM16,19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H16Zm0-9H4V9A1,1,0,0,1,5,8H15a1,1,0,0,1,1,1Zm4,5a1,1,0,0,1-1,1H18V9a3,3,0,0,0-.18-1H20Zm0-9H8V5A1,1,0,0,1,9,4H19a1,1,0,0,1,1,1Z"></path>
                    </svg>
                    Templates
                </button>
    
                <!-- Button: Settings (SVG only) -->
                <button @click="showSettings = !showSettings; showTemplates = false;" class="pt-1 text-gray-700 settings-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 448 512" fill="currentColor"><path d="M448 161v-21.3l-28.5-8.8-2.2-10.4 20.1-20.7L427 80.4l-29 7.5-7.2-7.5 7.5-28.2-19.1-11.6-21.3 21-10.7-3.2-7-26.4h-22.6l-6.2 26.4-12.1 3.2-19.7-21-19.4 11 8.1 27.7-8.1 8.4-28.5-7.5-11 19.1 20.7 21-2.9 10.4-28.5 7.8-.3 21.7 28.8 7.5 2.4 12.1-20.1 19.9 10.4 18.5 29.6-7.5 7.2 8.6-8.1 26.9 19.9 11.6 19.4-20.4 11.6 2.9 6.7 28.5 22.6.3 6.7-28.8 11.6-3.5 20.7 21.6 20.4-12.1-8.8-28 7.8-8.1 28.8 8.8 10.3-20.1-20.9-18.8 2.2-12.1 29.1-7zm-119.2 45.2c-31.3 0-56.8-25.4-56.8-56.8s25.4-56.8 56.8-56.8 56.8 25.4 56.8 56.8c0 31.5-25.4 56.8-56.8 56.8zm72.3 16.4l46.9 14.5V277l-55.1 13.4-4.1 22.7 38.9 35.3-19.2 37.9-54-16.7-14.6 15.2 16.7 52.5-38.3 22.7-38.9-40.5-21.7 6.6-12.6 54-42.4-.5-12.6-53.6-21.7-5.6-36.4 38.4-37.4-21.7 15.2-50.5-13.7-16.1-55.5 14.1-19.7-34.8 37.9-37.4-4.8-22.8-54-14.1.5-40.9L54 219.9l5.7-19.7-38.9-39.4L41.5 125l53.6 14.1 15.2-15.7-15.2-52 36.4-20.7 36.8 39.4L191 84l11.6-52H245l11.6 45.9L234 72l-6.3-1.7-3.3 5.7-11 19.1-3.3 5.6 4.6 4.6 17.2 17.4-.3 1-23.8 6.5-6.2 1.7-.1 6.4-.2 12.9C153.8 161.6 118 204 118 254.7c0 58.3 47.3 105.7 105.7 105.7 50.5 0 92.7-35.4 103.2-82.8l13.2.2 6.9.1 1.6-6.7 5.6-24 1.9-.6 17.1 17.8 4.7 4.9 5.8-3.4 20.4-12.1 5.8-3.5-2-6.5-6.8-21.2z"/></svg>
                </button>
    
                <!-- Toggle -->
                <label class="toggle-label">
                    <input type="checkbox" x-model="editMode" style="display: none !important">
                    <div class="toggle">
                        <div class="toggle__line"></div>
                        <div class="toggle__dot"></div>
                    </div>
                </label>
    
            </div> <!-- End of Flex Container -->
    <?php }

    public function area__settings($user_id) {
        ?>
        
            <!-- Templates Actions  - STARTS HERE -->
                <?php self::actions__bar('showSettings', 'settingsForm'); ?>
            <!-- Templates Actions  - ENDS HERE -->
            <div class="mt-10 w-[89%] sm:w-[94%] mx-auto">
                <!-- Hidden Input for Selected Template -->
                <form method="post" action="" id="settingsForm">
                <?php
                    $user_id = get_current_user_id();

                    $args = array(
                        'post_type' => 'hb-user-pkit',
                        'meta_query' => array(
                            array(
                                'key' => 'associated_pkit_user',
                                'value' => $user_id,
                                'compare' => '='
                            )
                        ),
                        'numberposts' => 1,
                    );

                    // Get the parent post
                    $parent = get_posts($args);

                    if (!empty($parent)) {
                        $parent_id = $parent[0]->ID;

                        $args_children = array(
                            'post_type' => 'hb-user-pkit',
                            'post_parent' => $parent_id,
                            'numberposts' => -1,
                            'post_status' => 'publish',
                        );

                        $children = get_posts($args_children);

                        $children_array = array();

                        foreach ($children as $child) {
                            // Create an associative array: [ID => slug]
                            $children_array[$child->ID] = $child->post_name;
                        }


                        // Loop through the associative array
                        foreach ($children_array as $i => $lang) {
                            // Call the function for each ID and slug
                            Plugin_Name_Builder::checkbox_field('public_' . $i, 
                                'Enable Public Access for "' . ucfirst($lang) . '"', 
                                Plugin_Name_Capabilities::EDIT_PROJECT_NAME, $user_id); 
                        }
                    }
                ?>

                <?php 

                         
                    Plugin_Name_Builder::checkbox_field('logo', 
                    'Hide the PRODUCHERTZ.COM logo', 
                    Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, $user_id); 
                
                    if(!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO)) {
                        echo '<div class="mt-6 warning-message"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Zm0-8.5a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0v-3A1,1,0,0,0,12,11.5Zm0-4a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,12,7.5Z"></path></svg><span>' . self::ERROR_HIDE_LOGO . '</span></div>';
                    }  
                 ?>
                </form>

                <?php

                // Check if the user is logged in
                if( is_user_logged_in() ) {
                    // Get the logout URL
                    $back = site_url('/my-account');

                    // Create a logout button
                    echo '<div class="flex"><a href="' . esc_url( $back ) . '" class="no-underline template-btn bg-[#171717] border-[#171717] hover:text-white text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M4,12a1,1,0,0,0,1,1h7.59l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l4-4a1,1,0,0,0,.21-.33,1,1,0,0,0,0-.76,1,1,0,0,0-.21-.33l-4-4a1,1,0,1,0-1.42,1.42L12.59,11H5A1,1,0,0,0,4,12ZM17,2H7A3,3,0,0,0,4,5V8A1,1,0,0,0,6,8V5A1,1,0,0,1,7,4H17a1,1,0,0,1,1,1V19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V16a1,1,0,0,0-2,0v3a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V5A3,3,0,0,0,17,2Z"></path></svg>Go back to My Account</a></div>';
                }



                ?>
                
            </div>
       
        <?php

    }
    
    

    public function component__range_picker() { ?>
        <!-- Custom Date Range Picker -->
        <form id="analyticsFilterForm" method="POST">
        <div x-show=" selectedRange == 'custom' ">
            <!-- <span class="block my-1 font-bold text-gray-700">Results</span> -->
            <input type="hidden" name="date_from" x-model="dateFromYmd">
            <input type="hidden" name="date_to" x-model="dateToYmd">
            <label for="datepicker" class="block mt-3 mb-1 font-bold text-gray-700">Select Date Range</label>
            <div class="relative" @keydown.escape="closeDatepicker()" @click.outside="closeDatepicker()">
                <div class="inline-flex items-center mt-3 bg-gray-200 border rounded-md">
                    <input type="text" @click="endToShow = 'from'; init(); showDatepicker = true" x-model="outputDateFromValue" :class="{'font-semibold': endToShow == 'from' }" class="w-40 p-2 border-0 border-r border-gray-300 focus:outline-none rounded-l-md"/>
                    <div class="inline-block h-full px-2">to</div>
                    <input type="text" @click="endToShow = 'to'; init(); showDatepicker = true" x-model="outputDateToValue" :class="{'font-semibold': endToShow == 'to' }" class="w-40 p-2 border-0 border-l border-gray-300 focus:outline-none rounded-r-md"/>
                </div>
                <div 
                    class="absolute p-4 mt-2 bg-white rounded-lg shadow" 
                    style="width: 17rem" 
                    x-show="showDatepicker"
                    x-transition
                >
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-between w-full mb-2">
                            <div>
                                <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                <span x-text="year" class="ml-1 text-lg font-normal text-gray-600"></span>
                            </div>
                            <div>
                                <button 
                                    type="button"
                                    class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200" 
                                    @click="if (month == 0) {year--; month=11;} else {month--;} getNoOfDays()">
                                    <svg class="inline-flex w-6 h-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>  
                                </button>
                                <button 
                                    type="button"
                                    class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200" 
                                    @click="if (month == 11) {year++; month=0;} else {month++;}; getNoOfDays()">
                                    <svg class="inline-flex w-6 h-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>									  
                                </button>
                            </div>
                        </div>
                        <div class="flex w-full mb-3 -mx-1">
                            <template x-for="(day, index) in DAYS" :key="index">	
                                <div style="width: 14.26%" class="px-1">
                                    <div x-text="day" class="text-xs font-medium text-center text-gray-800"></div>
                                </div>
                            </template>
                        </div>
                        <div class="flex flex-wrap -mx-1">
                            <template x-for="blankday in blankdays">
                                <div style="width: 14.28%" class="p-1 text-sm text-center border border-transparent"></div>
                            </template>	
                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">	
                                <div style="width: 14.28%">
                                    <div
                                        @click="getDateValue(date, false)"
                                        @mouseover="getDateValue(date, true)"
                                        x-text="date"
                                        class="p-1 text-sm leading-loose text-center transition duration-100 ease-in-out cursor-pointer"
                                        :class="{'font-bold': isToday(date) == true, 'bg-blue-800 text-white rounded-l-full': isDateFrom(date) == true, 'bg-blue-800 text-white rounded-r-full': isDateTo(date) == true, 'bg-blue-200': isInRange(date) == true }"	
                                    ></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <input type="submit" value="Filter" />
          </form>
            
    <?php }

    

    public function area__preview($user_id) { 
        $user_id = get_current_user_id(); 

        $elementor_page_url = get_user_meta( $user_id, 'username', true ); // Replace with the URL of your Elementor page
        
        echo '<div class="iframe-container">
        <div class="loaad">
        <div id="loading-spin"></div>
        Please hold on for a moment while we prepare your Link in Bio preview.</div><iframe src="' . esc_url(site_url('/bio') . '/' . $elementor_page_url) . '" style="width:100%;"></iframe></div>';
        ?> 
      <script>
            window.addEventListener("DOMContentLoaded", function() {
                var iframe = document.querySelector('iframe');
                var spinner = document.querySelector('.loaad');
                iframe.onload = function() {
                    iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 40 + 'px';
                    spinner.style.display = 'none'; // Hide spinner when iframe is loaded
                }

                  // Reload iframe every 30 seconds
                    setInterval(function(){
                        iframe.src += '';
                    }, 10000);
                    });
            
        </script>
        <?php
        
    }

    public function area__templates($user_id) { ?>
       <?php
		$selected = Plugin_Name_Utilities::handle_user_meta('selected_pkit_template', 'read', $user_id); 
		$default = get_user_meta(1, 'default_pkit_template', true);		
		?>
		<!-- Templates Actions  - STARTS HERE -->
        <?php self::actions__bar('showTemplates', 'templateForm'); ?>
		<!-- Templates Actions  - ENDS HERE -->

<!-- Filter Section -->
<div  class="flex w-[89%] sm:w-[94%] mx-auto items-center justify-start gap-4 pl-2 mt-10 sm:p-0">
    <span @click="activeFilter = 'all'" :class="{'text-gray-800 font-bold': activeFilter === 'all'}" class="cursor-pointer filter-item">All</span>
    <span @click="activeFilter = 'lite'" :class="{'text-gray-800 font-bold': activeFilter === 'lite'}" class="cursor-pointer filter-item">Free Template</span>
    <span @click="activeFilter = 'full'" :class="{'text-gray-800 font-bold': activeFilter === 'full'}" class="cursor-pointer filter-item">Pro Template</span>
</div>


				
<?php if (!Plugin_Name_Utilities::is_full_version($user_id)) {
    ?>
    <div class="w-[89%] sm:w-[94%] mx-auto" x-show="activeFilter !== 'lite'">

    <?php echo '<div class="mt-6 warning-message"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 576 512" fill="currentColor"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg><span>' . self::ERROR_TEMPLATE_MSG . '</span></div>'; ?>
    </div>


<?php }  ?>


        <?php 

		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$args = array(
			'post_type' => 'pkit-template',
			'posts_per_page' => 999,
			'paged' => $paged
		);
		$query = new WP_Query( $args );

		?>

		<?php if(isset($selected) && strlen($selected) > 0) { ?>
			<div x-data="{ selectedTemplate: '<?php echo $selected; ?>' }" class="mt-10 m-auto w-[90%] md:w-[95%]">
		<?php } else { ?>
			<?php if(isset($default) && strlen($default) > 0) { ?>
				<div x-data="{ selectedTemplate: '<?php echo $default; ?>' }" class="mt-10 m-auto w-[90%] md:w-[95%]">
			<?php } else { ?>
				<div x-data="{ selectedTemplate: '' }" class="mt-10 m-auto w-[90%] md:w-[95%]">
			<?php } ?>
		<?php } ?>
		

		

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php 
        // Get current user role
        $user = wp_get_current_user();
        $role = ( $user->roles ) ? $user->roles[0] : false;

        while( $query->have_posts() ) : $query->the_post(); 
            $version = get_post_meta(get_the_ID(), '_version_key', true);
            $version_display = ($version == 'lite') ? 'Free Template' : 'Pro Template';
            $is_disabled = ($role === 'lite-version' && $version === 'full' && $role !== 'administrator');
            
            if ($is_disabled):
        ?>
            <div class="no-underline opacity-50 template-card" x-show="activeFilter === 'all' || activeFilter === '<?php echo $version; ?>'" >
                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" class="object-cover w-full mb-2 h-44">
                <div class="p-1">
                    <div class="flex flex-col items-baseline mb-4">
                        <span class="template-version"><?php if($version_display === 'Pro Template') { echo '<svg xmlns="http://www.w3.org/2000/svg" height="0.7em" viewBox="0 0 576 512" fill="#f1441e" class="pt-[1px] mr-1 "><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>'; } ?><span><?php echo $version_display; ?></span></span>
                        <h2 class="template-title"><?php the_title(); ?></h2>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="#" 
               @click.prevent="selectedTemplate = '<?php the_ID(); ?>'" 
               class="no-underline template-card"
			   x-show="activeFilter === 'all' || activeFilter === '<?php echo $version; ?>'" 
               :class="{ 'border-[#F1441E] rounded shadow-xl border-2 transition-all': selectedTemplate === '<?php the_ID(); ?>' }">
                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" class="object-cover w-full mb-2 h-44">
                <div class="p-1">
                    <div class="flex flex-col items-baseline mb-4">
                        <span class="template-version"><?php if($version_display === 'Pro Template') { echo '<svg xmlns="http://www.w3.org/2000/svg" height="0.7em" viewBox="0 0 576 512" fill="#f1441e" class="pt-[1px] mr-1 "><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>'; } ?><span><?php echo $version_display; ?></span></span>
                        <h2 class="template-title"><?php the_title(); ?></h2>
                    </div>
                </div>
            </a>
        <?php endif; endwhile; ?>
    </div>
    
 
    <!-- Hidden Input for Selected Template -->
	<form method="post" action="" id="templateForm">
		<input type="hidden" x-model="selectedTemplate" name="selected_pkit_template">
	</form>
   

</div>
        
    <?php }
    
    public function area__edit($user_id) { ?>
            <!-- Tab Buttons - STARTS HERE -->
            <div class="tab-headers" x-show="!showTemplates">
                <button :class="{ 'active-tab': activeTab === 'profile' }" @click="activeTab = 'profile'" class="tab-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512" fill="currentColor"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                    Profile
                </button>
                <button :class="{ 'active-tab': activeTab === 'forms' }" @click="activeTab = 'forms'" class="tab-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512" fill="currentColor"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                    Artist Details
                </button>
                <button :class="{ 'active-tab': activeTab === 'analytics' }" @click="activeTab = 'analytics'" class="tab-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512" fill="currentColor"><path d="M24 32c13.3 0 24 10.7 24 24V408c0 13.3 10.7 24 24 24H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H72c-39.8 0-72-32.2-72-72V56C0 42.7 10.7 32 24 32zM128 136c0-13.3 10.7-24 24-24l208 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-208 0c-13.3 0-24-10.7-24-24zm24 72H296c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24s10.7-24 24-24zm0 96H424c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>
                    Analytics
                </button>
            </div>
            <!-- Tab Buttons - ENDS HERE -->
            <!-- Tabs Content - STARTS HERE -->
                <!-- Profile Tab Content - STARTS HERE -->
                <div x-show="activeTab === 'profile' && !showTemplates && !showSettings" class="pb-0 tab-content md:pt-5 md:px-8">
                    <?php self::edit__profile_tab($user_id); ?>
                </div>
                <!-- Profile Tab Content - ENDS HERE -->
                <!-- Artist Details Tab Content - STARTS HERE -->
                <div id="AcfFormsArea" x-show="activeTab === 'forms' && !showTemplates && !showSettings" class="pb-0 tab-content">
                    <?php self::edit__forms_tab($user_id); ?>
                </div>
                <!-- Artist Details Tab Content - ENDS HERE -->
                <!-- Analytics Tab Content - STARTS HERE -->
                <div x-show="activeTab === 'analytics' && !showTemplates && !showSettings" class="tab-content max-w-[700px] mt-10 pb-20 mx-auto">
                    <?php self::edit__tab_analytics($user_id);?>
                </div>
                <!-- Analytics Tab Content - ENDS HERE -->
            <!-- Tabs Content - ENDS HERE -->

    <?php }

    public function edit__tab_analytics($user_id) { ?>
        <div x-data="analyticsFilter()" x-init="init" x-cloak>
            <!-- Predefined Date Range Filters -->
            <div class="flex items-center justify-start gap-4 mb-4">
                <span @click="setDateRange('lifetime')" :class="{'font-bold text-gray-800': selectedRange == 'lifetime'}" class="cursor-pointer filter-item">Lifetime</span>
                <span @click="setDateRange('7days')" :class="{'font-bold text-gray-800': selectedRange == '7days'}" class="cursor-pointer filter-item">Last 7 days</span>
                <span @click="setDateRange('30days')" :class="{'font-bold text-gray-800': selectedRange == '30days'}" class="cursor-pointer filter-item">Last 30 days</span>
                <span @click="setDateRange('90days')" :class="{'font-bold text-gray-800': selectedRange == '90days'}" class="cursor-pointer filter-item">Last 90 days</span>
                <span @click="setDateRange('custom')" :class="{'font-bold text-gray-800': selectedRange == 'custom'}" class="cursor-pointer filter-item">Custom</span>
            </div>

            <?php self::component__range_picker(); ?>
          
            <?php
            $title = get_user_meta($user_id, 'username', true);
            $post = get_page_by_path( $title, OBJECT, 'hb-user-profile' );
           
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['date_from']) && isset($_POST['date_to'])) {
                    $date_from = $_POST['date_from'];
                    $date_to = $_POST['date_to'];
            
                   
                    echo "<div class='mt-3 mb-0 input-label'>Top Performing Links</div>";
                    echo "<div class='table-wrapper'>";     
                    if(Plugin_Name_Utilities::is_full_version($user_id)) {
                        echo do_shortcode('[wpdatatable id=2 var1=' . $date_from . ' var2=' . $date_to . ' var3=' . $user_id . ']');
                    } else {
                        echo do_shortcode('[wpdatatable id=4 var1=' . $date_from . ' var2=' . $date_to . ' var3=' . $user_id . ']');
                    }
                    echo "</div>";
                    
                    // Hidden Tables
                    echo do_shortcode('[wpdatatable id=7 var1=' . $post->ID . ' var2=' . $date_from . ' var3=' . $date_to . ']');
                    echo do_shortcode('[wpdatatable id=15 var1=' . $date_from . ' var2=' . $date_to . ' var3=' . $user_id . ']');
                   
                    echo "<div class='mt-3 input-label'>Total Page Views</div>";
                    echo "<div class='chart-wraper'>";        
                    echo do_shortcode('[wpdatachart id=2]');
                    echo "</div>";

                    echo "<div class='mt-3 input-label'>Click Through Rate</div>";
                    echo "<div class='table-wrapper'>";     
                    echo do_shortcode('[wpdatatable id=14 var1=' . $post->ID . ' var2=' . $date_from . ' var3=' . $date_to . ']');
                    echo "</div>";

                    echo "<div class='mt-3 input-label'>Social Links</div>";
                    echo "<div class='chart-wraper'>";        
                    echo do_shortcode('[wpdatachart id=3]');
                    echo "</div>";
                    
                    
                } 
            } else {
                echo "<div class='mt-3 mb-0 input-label'>Top Performing Links</div>";
                echo "<div class='table-wrapper'>";        
                if(Plugin_Name_Utilities::is_full_version($user_id)) {
                    echo do_shortcode('[wpdatatable id=2 var1=1970-01-01 var2=' . date("Y-m-d") . ' var3=' . $user_id . ']');
                } else {
                    echo do_shortcode('[wpdatatable id=4 var1=1970-01-01 var2=' . date("Y-m-d") . ' var3=' . $user_id . ']');
                }
                echo "</div>";
                 
                // Hidden Tables
                echo do_shortcode('[wpdatatable id=7 var1=' . $post->ID . ' var2=1970-01-01 var3=' . date("Y-m-d") . ']');
                echo do_shortcode('[wpdatatable id=15 var1=1970-01-01 var2=' . date("Y-m-d") . ' var3=' . $user_id . ']');

               
                echo "<div class='mt-3 input-label'>Total Page Views</div>";
                echo "<div class='chart-wraper'>";        
                echo do_shortcode('[wpdatachart id=2]');
                echo "</div>";
                echo "<div class='mt-3 input-label'>Click Through Rate</div>";
                echo "<div class='table-wrapper'>";       
                echo do_shortcode('[wpdatatable id=14 var1=' . $post->ID . ' var2=1970-01-01 var3=' . date("Y-m-d") . ']');
                echo "</div>";

                echo "<div class='mt-3 input-label'>Social Links</div>";
                echo "<div class='chart-wraper'>";        
                echo do_shortcode('[wpdatachart id=3]');
                echo "</div>";

               


               
                
               
            }
            
                
            ?>
        </div>  

    <?php }
    
    public function edit__forms_tab($user_id) { 
                   
        $forms = Plugin_Name_Utilities::get_user_forms(Plugin_Name_Utilities::get_user_langs()); 
        foreach($forms as $form) {
            echo do_shortcode('[advanced_form form="' . $form . '" user="current"]');
        }
    }

    

    public function edit__profile_tab($user_id) { ?>
        <?php Plugin_Name_Builder::upload_field('pkit_profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id, in_array("pkit_profile_photo_url", $this->dynamic_tags)); ?>
        <?php Plugin_Name_Builder::upload_field('pkit_cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id, in_array("pkit_cover_photo_url", $this->dynamic_tags)); ?>
        
        <form method="post">
            <?php 
            Press_Kit_Builder::url_field('pkit_username', 
                                        'Username', 
                                        false,
                                        'Username', 
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M13.3,12.22A4.92,4.92,0,0,0,15,8.5a5,5,0,0,0-10,0,4.92,4.92,0,0,0,1.7,3.72A8,8,0,0,0,2,19.5a1,1,0,0,0,2,0,6,6,0,0,1,12,0,1,1,0,0,0,2,0A8,8,0,0,0,13.3,12.22ZM10,11.5a3,3,0,1,1,3-3A3,3,0,0,1,10,11.5ZM21.71,9.13a1,1,0,0,0-1.42,0l-2,2-.62-.63a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l1.34,1.34a1,1,0,0,0,1.41,0l2.67-2.67A1,1,0,0,0,21.71,9.13Z"></path></svg>', 
                                        Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id); 
            ?>
            
            <?php 
            Plugin_Name_Builder::text_field('pkit_project',
                                        'Project / Artist', 
                                        false,
                                        'Project / Artist', 
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M15.8985,13.229l-.00427-.00183c-.06336-.02673-.12439-.05774-.18836-.08313A5.98759,5.98759,0,0,0,18,8.43457a5.2899,5.2899,0,0,0-.04468-.63049.94592.94592,0,0,0,.03784-.12195l.28125-2.39746A3.00567,3.00567,0,0,0,15.83154,1.9834l-.79-.14356a16.931,16.931,0,0,0-6.08252,0l-.791.14356A3.0057,3.0057,0,0,0,5.72559,5.28467l.28125,2.39746a.94592.94592,0,0,0,.03784.122A5.2899,5.2899,0,0,0,6,8.43457,5.98759,5.98759,0,0,0,8.29413,13.144c-.064.02539-.125.0564-.18836.08313L8.1015,13.229a9.94794,9.94794,0,0,0-6.03558,8.09717,1,1,0,0,0,1.98828.2168A7.94836,7.94836,0,0,1,8.26965,15.358L11.293,18.38135a.99963.99963,0,0,0,1.41406,0L15.73035,15.358A7.94836,7.94836,0,0,1,19.9458,21.543a.99992.99992,0,0,0,.99268.8916,1.048,1.048,0,0,0,.10986-.00586,1.00007,1.00007,0,0,0,.88574-1.10254A9.94794,9.94794,0,0,0,15.8985,13.229ZM7.71191,5.05127a1.00179,1.00179,0,0,1,.814-1.1001l.79053-.14355a14.92975,14.92975,0,0,1,5.36718,0l.79.14355a1.00176,1.00176,0,0,1,.81446,1.1001l-.17774,1.51416H7.88965ZM12,16.26025,10.34973,14.61a7.8502,7.8502,0,0,1,3.30054,0Zm0-3.82568A4.005,4.005,0,0,1,8.002,8.56543h7.9961A4.005,4.005,0,0,1,12,12.43457Z"></path></svg>', 
                                        Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id, in_array("pkit_project", $this->dynamic_tags) ); 
            ?>

            <?php Plugin_Name_Utilities::get_user_forms(Plugin_Name_Utilities::get_user_langs()); ?>
            <?php
            Press_Kit_Builder::language_select('pkit_lang', 'en', 'Language', Plugin_Name_Capabilities::PRESSKIT_LANG, $user_id);
            ?>
            <div class="save-progress">
                <div class="save-progress-contain">

                    <div>Use the "Update" button to save your changes!</div>
                    <input type="submit" name="submit_form" value="Update" class="h-10 mt-0 text-base upload-btn sm:text-sm">
                </div>
            </div>
        </form>

    <?php }

    public function actions__bar($handle, $formHandler) { ?>
        <!-- Flex container with space between "Back" and "Save" buttons -->
        <div class="flex items-center justify-between mt-6 w-[89%] sm:w-[94%] m-auto">
            <!-- Back Button for Templates -->
            <button @click="<?php echo $handle ?> = false" class="template-btn bg-[#171717] border-[#171717]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
                </svg>
                Back
            </button>

            <!-- Save Button -->
            <button @click="document.getElementById('<?php echo $formHandler; ?>').submit();" class="template-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <!-- SVG path for a save icon -->
                    <path d="M17 3H7a2 2 0 0 0-2 2v16l7-3 7 3V5a2 2 0 0 0-2-2zm-5 12a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"></path>
                </svg>
                Save
            </button>
        </div>
    <?php }
    
}

// Instantiate the class
new Press_Kit_Dashboard();