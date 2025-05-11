$(document).ready(function(){
    $('.autoplay').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        dots: false,
        infinite: true,
        speed: 600,
        fade: true,
        cssEase: 'linear',
        prevArrow: `
          <button type="button"
              class="slick-prev absolute left-3 top-1/2 transform -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7"/>
              </svg>
          </button>
        `,
        nextArrow: `
          <button type="button"
              class="slick-next absolute right-3 top-1/2 transform -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
              </svg>
          </button>
        `
    });
});
