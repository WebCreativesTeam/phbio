<?php
  $logo_img = plugin_dir_url( __FILE__ ) . 'img/Produchertz.com-Official-Logo.png';
?>

<footer class="mt-10 overflow-hidden bg-black w-full">
    <div class="w-full max-w-screen-xl px-6 py-10 mx-auto text-white box-border">
      <div
        class="flex flex-col items-center justify-center gap-8 text-center md:flex-row md:text-left md:justify-between">
        <div class="space-y-1">
          <img  class="md:order-1 w-full max-w-[200px] mx-auto mb-5 md:mx-0" src = "<?php echo $logo_img; ?>"/>
          <p class="font-normal text-[#4f504a]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem,
            at!</p>
          <p class="text-[#4f504a] font-normal  md:block">@2023 PRODUCHERTZ.COM</p>
        </div>
        <div class="flex flex-col gap-4 md:text-center lg:flex-row lg:gap-6">
          <a href="#" class="font-light">Privacy Policy</a>
          <a href="#" class="font-light">Cookie Policy</a>
          <a href="#"
            class="text-white flex items-center gap-2 bg-[#F1441E] p-1 px-6 rounded-lg w-fit lg:bg-transparent lg:font-light lg:p-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-[#F1441E] hidden lg:block"
              viewBox="0 0 512 512">
              <path
                d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
            </svg>
            <span>My Account</span>
          </a>
        </div>
        <p class="text-[#4f504a] text-light mt-5 md:hidden">@2023 PRODUCHERTZ.COM</p>
      </div>
    </div>
  </footer>