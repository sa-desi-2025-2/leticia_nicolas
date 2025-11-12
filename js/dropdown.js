// dropdown.js

// Dropdown do usuário (ícone de perfil)
const userButton = document.getElementById('userButton');
const dropdownMenu = document.getElementById('dropdownMenu');

if (userButton && dropdownMenu) {
    userButton.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });
}

// Fecha o dropdown do usuário ao clicar fora
window.addEventListener('click', () => {
    if (dropdownMenu) dropdownMenu.classList.remove('show');
});
