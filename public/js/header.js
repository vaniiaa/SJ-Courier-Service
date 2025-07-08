// JavaScript tambahan untuk sticky navbar
window.onscroll = function() {stickyNavbar()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function stickyNavbar() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("fixed", "top-0", "left-0", "w-full");
        navbar.classList.remove("sticky");
    } else {
        navbar.classList.remove("fixed", "top-0", "left-0", "w-full");
        navbar.classList.add("sticky");
    }
}
