const menuButton = document.querySelector('#menu-btn');
const menu = document.querySelector('#nav-menu');

// show or hide
menuButton.addEventListener('click',function(){
    menu.classList.toggle('show-menu');
    menuButton.classList.toggle('close');
});