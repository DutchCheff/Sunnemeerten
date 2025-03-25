document.addEventListener("DOMContentLoaded", () => {
    const menuButton = document.querySelector(".mobile-menu-button");
    const menu = document.querySelector(".mobile-menu");

    menuButton.addEventListener("click", () => {
        event.stopPropagation();
        menu.classList.toggle("hidden");
        if (menuButton.classList.contains("fa-bars")) {
            menuButton.classList.remove("fa-bars");
            menuButton.classList.add("fa-x");
        } else {
            menuButton.classList.remove("fa-x");
            menuButton.classList.add("fa-bars");
        }
    });

    document.addEventListener("click", (event) => {
        if (!menu.contains(event.target) && !menuButton.contains(event.target)) {
            menu.classList.add("hidden");
            menuButton.classList.remove("fa-x");
            menuButton.classList.add("fa-bars");
        }
    });
});
