<?php

class Plugin_Name_Dashboard {

    private $dynamic_tags = Array();
    
    public function __construct() {
        add_action( 'admin_menu', array($this, 'register') );
    }
    
    public function register() {
       add_menu_page(
			'Profile',             
			'Profile',                
			'read',             
			'profile-editor',        
			array($this, 'render'),       
			'dashicons-admin-generic',    
			100                           
		);
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
		?>
<div class="dashboard-layout">

<div x-data="{ editMode: false, activeTab: 'profile', showSettings: false, showTemplates: false, activeFilter: 'all',
    saveState: function() {
            localStorage.setItem('alpineState', JSON.stringify({
                editMode: this.editMode,
                activeTab: this.activeTab,
                showSettings: this.showSettings,
                showTemplates: this.showTemplates,
                activeFilter: this.activeFilter
            }));
        } }" 
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
        $watch('editMode', () => saveState());
        $watch('activeTab', () => saveState());
        $watch('showSettings', () => saveState());
        $watch('showTemplates', () => saveState());
        $watch('activeFilter', () => saveState());
    }"
     class="relative main-area"> <!-- Added relative positioning here -->

    <!--Top Actions - STARTS HERE -->
    <div class="actions-area">
        <?php self::top_actions(); ?>
    </div>
    <!--Top Actions - ENDS HERE -->

    <!-- Settings Content - STARTS HERE -->
    <div x-show="showSettings" class="content-settings">
        <?php self::area__settings($user_id); ?>
    </div>
    <!-- Settings Content - ENDS HERE -->


    <!-- Templates Content Area - STARTS HERE -->
    <div x-show="showTemplates" class="content-templates">
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

</div>



	

	
	<?php
	}
    public function top_actions() { ?>

            <h1 x-text="!editMode ? 'Edit Mode' : 'Preview Mode' " class="page-title"></h1>
    
            <!-- New Flex Container for Buttons and Toggle -->
            <div class="action-buttons">
    
                <!-- Button: Select Template -->
                <button @click="showTemplates = !showTemplates; showSettings = false;" class="template-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19,2H9A3,3,0,0,0,6,5V6H5A3,3,0,0,0,2,9V19a3,3,0,0,0,3,3H15a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2ZM16,19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H16Zm0-9H4V9A1,1,0,0,1,5,8H15a1,1,0,0,1,1,1Zm4,5a1,1,0,0,1-1,1H18V9a3,3,0,0,0-.18-1H20Zm0-9H8V5A1,1,0,0,1,9,4H19a1,1,0,0,1,1,1Z"></path>
                    </svg>
                    Select Template
                </button>
    
                <!-- Button: Settings (SVG only) -->
                <button @click="showSettings = !showSettings; showTemplates = false;" class="settings-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"></path>
                    </svg>
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
            <div class="mt-10 ml-5">
                <!-- Hidden Input for Selected Template -->
                <form method="post" action="" id="settingsForm">
                <?php 
                    Plugin_Name_Builder::checkbox_field('public', 
                    'Enable Public Access', 
                    Plugin_Name_Capabilities::EDIT_PROJECT_NAME, $user_id); 
                    ?>
                <?php 
                
                    Plugin_Name_Builder::checkbox_field('logo', 
                    'Hide the PRODUCHERTZ.COM logo', 
                    Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, $user_id); 
                
                    
                 ?>
                </form>
                
            </div>
       
        <?php
    }
    
    

    public function component__range_picker() { ?>
        <!-- Custom Date Range Picker -->
        <div>
            <span class="block my-1 font-bold text-gray-700">Results</span>
            <input type="text" name="date_from" x-model="dateFromYmd">
            <input type="text" name="date_to" x-model="dateToYmd">
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
                        <div class="flex flex-wrap w-full mb-3 -mx-1">
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
            
    <?php }

    

    public function area__preview($user_id) { ?>
       hi
        
    <?php }

    public function area__templates($user_id) { ?>
       <?php
		$selected = Plugin_Name_Utilities::handle_user_meta('selected_template', 'read', $user_id); 
		$default = get_user_meta(1, 'default_template', true);		
		?>
		<!-- Templates Actions  - STARTS HERE -->
        <?php self::actions__bar('showTemplates', 'templateForm'); ?>
		<!-- Templates Actions  - ENDS HERE -->

<!-- Filter Section -->
<div  class="flex items-center justify-start gap-4 mt-10 ml-4">
    <span @click="activeFilter = 'all'" :class="{'text-gray-800 font-bold': activeFilter === 'all'}" class="cursor-pointer filter-item">All</span>
    <span @click="activeFilter = 'full'" :class="{'text-gray-800 font-bold': activeFilter === 'full'}" class="cursor-pointer filter-item">Full Version</span>
    <span @click="activeFilter = 'lite'" :class="{'text-gray-800 font-bold': activeFilter === 'lite'}" class="cursor-pointer filter-item">Lite Version</span>
</div>


        <?php 

		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$args = array(
			'post_type' => 'template-manager',
			'posts_per_page' => 999,
			'paged' => $paged
		);
		$query = new WP_Query( $args );

		?>

		<?php if(isset($selected) && strlen($selected) > 0) { ?>
			<div x-data="{ selectedTemplate: '<?php echo $selected; ?>' }" class="mt-10 ml-4">
		<?php } else { ?>
			<?php if(isset($default) && strlen($default) > 0) { ?>
				<div x-data="{ selectedTemplate: '<?php echo $default; ?>' }" class="mt-10 ml-4">
			<?php } else { ?>
				<div x-data="{ selectedTemplate: '' }" class="mt-10 ml-4">
			<?php } ?>
		<?php } ?>
		

		

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
        <?php 
        // Get current user role
        $user = wp_get_current_user();
        $role = ( $user->roles ) ? $user->roles[0] : false;

        while( $query->have_posts() ) : $query->the_post(); 
            $version = get_post_meta(get_the_ID(), '_version_key', true);
            $version_display = ($version == 'lite') ? 'Lite Version' : 'Full Version';
            $is_disabled = ($role === 'lite-version' && $version === 'full' && $role !== 'administrator');
            
            if ($is_disabled):
        ?>
            <div class="no-underline opacity-50 template-card" x-show="activeFilter === 'all' || activeFilter === '<?php echo $version; ?>'" >
                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" class="object-cover w-full mb-2 rounded-t h-44">
                <div class="p-1">
                    <div class="flex flex-col items-baseline mb-4 ml-4 sm:flex-row">
                        <span class="template-version"><?php echo $version_display; ?></span>
                        <h2 class="template-title"><?php the_title(); ?></h2>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="#" 
               @click.prevent="selectedTemplate = '<?php the_ID(); ?>'" 
               class="no-underline template-card"
			   x-show="activeFilter === 'all' || activeFilter === '<?php echo $version; ?>'" 
               :class="{ 'border-gray-800 rounded shadow-xl border-4 transition-all': selectedTemplate === '<?php the_ID(); ?>' }">
                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" class="object-cover w-full mb-2 rounded-t h-44">
                <div class="p-1">
                    <div class="flex flex-col items-baseline mb-4 ml-4 sm:flex-row">
                        <span class="template-version"><?php echo $version_display; ?></span>
                        <h2 class="template-title"><?php the_title(); ?></h2>
                    </div>
                </div>
            </a>
        <?php endif; endwhile; ?>
    </div>
    
 
    <!-- Hidden Input for Selected Template -->
	<form method="post" action="" id="templateForm">
		<input type="hidden" x-model="selectedTemplate" name="selected_template">
	</form>
   

</div>
        
    <?php }
    
    public function area__edit($user_id) { ?>
            <!-- Tab Buttons - STARTS HERE -->
            <div class="tab-headers">
                <button :class="{ 'active-tab': activeTab === 'profile' }" @click="activeTab = 'profile'" class="tab-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"></path></svg>
                    Profile
                </button>
                <button :class="{ 'active-tab': activeTab === 'links' }" @click="activeTab = 'links'" class="tab-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.11,15.39,8.23,19.27a2.47,2.47,0,0,1-3.5,0,2.46,2.46,0,0,1,0-3.5l3.88-3.88a1,1,0,1,0-1.42-1.42L3.31,14.36a4.48,4.48,0,0,0,6.33,6.33l3.89-3.88a1,1,0,0,0-1.42-1.42Zm-3.28-.22a1,1,0,0,0,.71.29,1,1,0,0,0,.71-.29l4.92-4.92a1,1,0,1,0-1.42-1.42L8.83,13.75A1,1,0,0,0,8.83,15.17ZM21,18H20V17a1,1,0,0,0-2,0v1H17a1,1,0,0,0,0,2h1v1a1,1,0,0,0,2,0V20h1a1,1,0,0,0,0-2Zm-4.19-4.47,3.88-3.89a4.48,4.48,0,0,0-6.33-6.33L10.47,7.19a1,1,0,1,0,1.42,1.42l3.88-3.88a2.47,2.47,0,0,1,3.5,0,2.46,2.46,0,0,1,0,3.5l-3.88,3.88a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0Z"></path></svg>
                    Links
                </button>
                <button :class="{ 'active-tab': activeTab === 'analytics' }" @click="activeTab = 'analytics'" class="tab-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M5,12a1,1,0,0,0-1,1v8a1,1,0,0,0,2,0V13A1,1,0,0,0,5,12ZM10,2A1,1,0,0,0,9,3V21a1,1,0,0,0,2,0V3A1,1,0,0,0,10,2ZM20,16a1,1,0,0,0-1,1v4a1,1,0,0,0,2,0V17A1,1,0,0,0,20,16ZM15,8a1,1,0,0,0-1,1V21a1,1,0,0,0,2,0V9A1,1,0,0,0,15,8Z"></path></svg>
                    Analytics
                </button>
            </div>
            <!-- Tab Buttons - ENDS HERE -->
            <!-- Tabs Content - STARTS HERE -->
                <!-- Profile Tab Content - STARTS HERE -->
                <div x-show="activeTab === 'profile'" class="tab-content">
                    <?php self::edit__profile_tab($user_id); ?>
                </div>
                <!-- Profile Tab Content - ENDS HERE -->
                <!-- Links Tab Content - STARTS HERE -->
                <div x-show="activeTab === 'links'" class="tab-content">
                    <?php self::edit__links_tab($user_id); ?>
                </div>
                <!-- Links Tab Content - ENDS HERE -->
                <!-- Analytics Tab Content - STARTS HERE -->
                <div x-show="activeTab === 'analytics'" class="tab-content">
                    <?php self::edit__tab_analytics($user_id);?>
                </div>
                <!-- Analytics Tab Content - ENDS HERE -->
            <!-- Tabs Content - ENDS HERE -->

    <?php }

    public function edit__tab_analytics($user_id) { ?>
        <div x-data="analyticsFilter()" x-cloak>
            <!-- Predefined Date Range Filters -->
            <div class="flex items-center justify-start gap-4 mb-4">
                <span @click="setDateRange('lifetime')" :class="{'font-bold text-gray-800': selectedRange == 'lifetime'}" class="cursor-pointer filter-item">Lifetime</span>
                <span @click="setDateRange('7days')" :class="{'font-bold text-gray-800': selectedRange == '7days'}" class="cursor-pointer filter-item">Last 7 days</span>
                <span @click="setDateRange('30days')" :class="{'font-bold text-gray-800': selectedRange == '30days'}" class="cursor-pointer filter-item">Last 30 days</span>
                <span @click="setDateRange('90days')" :class="{'font-bold text-gray-800': selectedRange == '90days'}" class="cursor-pointer filter-item">Last 90 days</span>
            </div>

            <?php self::component__range_picker(); ?>
        </div>  

    <?php }
    
    public function edit__links_tab($user_id) { ?>
        <?php echo Plugin_Name_Utilities::current_user_has_backup_links(); ?>
        <form method="post">
            <?php Plugin_Name_Builder::link_list_field( 'Manage Links', Plugin_Name_Capabilities::EDIT_LINKS, $user_id); ?>
            <?php Plugin_Name_Builder::social_links_list_field( 'Manage Social Links', Plugin_Name_Capabilities::EDIT_LINKS, $user_id); ?>
            <input type="submit" name="submit_form" value="Update" class="upload-btn">
        </form>      
    <?php }

    public function edit__profile_tab($user_id) { ?>
        <?php Plugin_Name_Builder::upload_field('profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id, in_array("profile_photo_url", $this->dynamic_tags)); ?>
        <?php Plugin_Name_Builder::upload_field('cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id, in_array("cover_photo_url", $this->dynamic_tags)); ?>
        <form method="post">
            <?php 
            Plugin_Name_Builder::url_field('username', 
                                        'Username', 
                                        false,
                                        'Username', 
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M13.3,12.22A4.92,4.92,0,0,0,15,8.5a5,5,0,0,0-10,0,4.92,4.92,0,0,0,1.7,3.72A8,8,0,0,0,2,19.5a1,1,0,0,0,2,0,6,6,0,0,1,12,0,1,1,0,0,0,2,0A8,8,0,0,0,13.3,12.22ZM10,11.5a3,3,0,1,1,3-3A3,3,0,0,1,10,11.5ZM21.71,9.13a1,1,0,0,0-1.42,0l-2,2-.62-.63a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l1.34,1.34a1,1,0,0,0,1.41,0l2.67-2.67A1,1,0,0,0,21.71,9.13Z"></path></svg>', 
                                        Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id); 
            ?>
            <?php 
            Plugin_Name_Builder::text_field('project',
                                        'Project / Artist', 
                                        false,
                                        'Project / Artist', 
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M15.8985,13.229l-.00427-.00183c-.06336-.02673-.12439-.05774-.18836-.08313A5.98759,5.98759,0,0,0,18,8.43457a5.2899,5.2899,0,0,0-.04468-.63049.94592.94592,0,0,0,.03784-.12195l.28125-2.39746A3.00567,3.00567,0,0,0,15.83154,1.9834l-.79-.14356a16.931,16.931,0,0,0-6.08252,0l-.791.14356A3.0057,3.0057,0,0,0,5.72559,5.28467l.28125,2.39746a.94592.94592,0,0,0,.03784.122A5.2899,5.2899,0,0,0,6,8.43457,5.98759,5.98759,0,0,0,8.29413,13.144c-.064.02539-.125.0564-.18836.08313L8.1015,13.229a9.94794,9.94794,0,0,0-6.03558,8.09717,1,1,0,0,0,1.98828.2168A7.94836,7.94836,0,0,1,8.26965,15.358L11.293,18.38135a.99963.99963,0,0,0,1.41406,0L15.73035,15.358A7.94836,7.94836,0,0,1,19.9458,21.543a.99992.99992,0,0,0,.99268.8916,1.048,1.048,0,0,0,.10986-.00586,1.00007,1.00007,0,0,0,.88574-1.10254A9.94794,9.94794,0,0,0,15.8985,13.229ZM7.71191,5.05127a1.00179,1.00179,0,0,1,.814-1.1001l.79053-.14355a14.92975,14.92975,0,0,1,5.36718,0l.79.14355a1.00176,1.00176,0,0,1,.81446,1.1001l-.17774,1.51416H7.88965ZM12,16.26025,10.34973,14.61a7.8502,7.8502,0,0,1,3.30054,0Zm0-3.82568A4.005,4.005,0,0,1,8.002,8.56543h7.9961A4.005,4.005,0,0,1,12,12.43457Z"></path></svg>', 
                                        Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id, in_array("project", $this->dynamic_tags) ); 
            ?>

            <?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio', Plugin_Name_Capabilities::EDIT_BIO, false, $user_id, in_array("bio", $this->dynamic_tags)); ?>
            <input type="submit" name="submit_form" value="Update" class="upload-btn">
        </form>
    <?php }

    public function actions__bar($handle, $formHandler) { ?>
        <!-- Flex container with space between "Back" and "Save" buttons -->
        <div class="flex items-center justify-between mt-6 ml-4">
            <!-- Back Button for Templates -->
            <button @click="<?php echo $handle ?> = false" class="template-btn">
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