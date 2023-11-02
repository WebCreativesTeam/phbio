<?php
  $current_page = $_GET['page'] ?? '';
  $logo_img = plugin_dir_url( __FILE__ ) . 'img/Produchertz.com-Official-Logo.png';
?>

<header class="w-full py-6 md:py-2 mt-0 mb-10 overflow-hidden bg-black text-sm md:text-[15px]">
    <div class="box-border w-full max-w-screen-xl px-4 py-4 mx-auto text-white sm:px-6 sm:py-4">
      <div class="flex flex-wrap items-center justify-between gap-0 sm:gap-5 md:flex-nowrap md:justify-normal md:gap-12 lg:gap-24">
        <img  class="md:order-1 w-[180px] sm:w-[225px]" src ="<?php echo $logo_img; ?>"/>
        <a href="https://produchertz.com/my-account" target="_blank" class="text-white flex items-center h-fit md:bg-[#F1441E] p-1 px-2 md:p-2 rounded-md gap-2 md:order-3 hover:text-white">
          <span class="md:order-2">My Account</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-[#F1441E]  md:fill-white md:order-1 md:w-4 md:h-4"
            viewBox="0 0 512 512">
            <path
              d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
          </svg>
        </a>
        <div
          class="flex mt-4 overflow-hidden font-medium text-center capitalize sm:mt-0 rounded-2xl basis-full md:min-w-0 md:basis-auto md:order-2 md:ml-auto md:gap-2 md:font-normal">
          <a href="<?php echo admin_url('admin.php?page=profile-editor'); ?>" class="flex-grow bg-[#2f2f2f] flex items-center justify-center gap-2  p-2  md:bg-transparent  text-white focus:text-[#F1441E] focus:shadow-none <?php if ($current_page === 'profile-editor') echo 'current-page'; ?>">
            
              <svg xmlns="http://www.w3.org/2000/svg" 
                class="w-5 h-5 fill-current stroke-2"
                viewBox="0 0 640 512">
                <path
                  d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z" />
              </svg>
            <span>
              Link in Bio
            </span>
          </a>
          <a href="<?php echo admin_url('admin.php?page=my-presskit'); ?>" class="flex-grow text-white bg-[#2f2f2f] p-2 flex justify-center items-center gap-2 md:bg-transparent focus:text-[#F1441E] focus:shadow-none <?php if ($current_page === 'my-presskit') echo 'current-page'; ?>">
            <span class="md:order-2">
              Press kit
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current stroke-2 md:order-1" viewBox="0 0 384 512"><path d="M256 48V64c0 17.7-14.3 32-32 32H160c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16H256zM0 64C0 28.7 28.7 0 64 0H320c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zM160 320h64c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16c0-44.2 35.8-80 80-80zm-32-96a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"/></svg>
          </a>
        </div>
      </div>
    </div>
  </header>
