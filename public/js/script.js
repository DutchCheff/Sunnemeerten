const burger = document.getElementById('burger');
const closeMenu = document.getElementById('close-menu');
const menu = document.getElementById('menu');

burger.addEventListener('click', () => {
    menu.classList.toggle('translate-y-full');
});

closeMenu.addEventListener('click', () => {
    menu.classList.add('translate-y-full');
});