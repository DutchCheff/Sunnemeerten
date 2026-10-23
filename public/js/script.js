const burger = document.getElementById('burger');
const closeMenu = document.getElementById('close-menu');
const menu = document.getElementById('menu');

if (burger) {
    burger.addEventListener('click', () => {
        menu.classList.toggle('translate-x-full');
    });

    closeMenu.addEventListener('click', () => {
        menu.classList.add('translate-x-full');
    });
}

document.querySelectorAll('[data-dismiss]').forEach((message) => {
    message.addEventListener('click', () => message.remove());
    setTimeout(() => message.remove(), 5000);
});

// Vraagt om bevestiging bij knoppen zoals verwijderen
document.querySelectorAll('[data-confirm]').forEach((element) => {
    element.addEventListener('submit', (event) => {
        if (!confirm(element.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
