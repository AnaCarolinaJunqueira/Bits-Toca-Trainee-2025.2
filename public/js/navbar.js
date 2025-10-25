const menuHamburger = document.querySelector("#menu-icone");
const menuItens = document.querySelector(".menu-itens");

menuHamburger.addEventListener("click", () => {
    menuItens.classList.toggle("active");

    menuHamburger.classList.toggle("bi-list");
    menuHamburger.classList.toggle("bi-x-square");
});

menuItens.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
    menuItens.classList.remove("active");

    menuHamburger.classList.toggle("bi-x-square");
    menuHamburger.classList.toggle("bi-list");
}));