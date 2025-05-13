var init = false;
var swiper;

document.addEventListener('DOMContentLoaded', function () {
    var slider = document.querySelector('.swiper');
    if (!slider) return; // Salir de la función si el elemento .swiper no existe
    swiperCard();

});
window.addEventListener("resize", function () {
    var slider = document.querySelector('.swiper');
    if (!slider) return; // Salir de la función si el elemento .swiper no existe
    swiperCard();
});
function swiperCard() {
    console.log(window.innerWidth);


    if (window.innerWidth <= 768) {
        if (!init) {
            init = true;
            swiper = new Swiper(".swiper", {
                slidesPerView: 'auto',
                spaceBetween: 16,
                centeredSlides: false,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },

            });
        }
    } else if (init && swiper) { // Verificar si swiper está definido antes de destruirlo
        swiper.destroy(); // Destruir Swiper y limpiar todos los eventos y clases
        init = false;
    }
}
