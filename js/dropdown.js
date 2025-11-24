
const userButton = document.getElementById('userButton');
const dropdownMenu = document.getElementById('dropdownMenu');

if (userButton && dropdownMenu) {
    userButton.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });
}


window.addEventListener('click', () => {
    if (dropdownMenu) dropdownMenu.classList.remove('show');
});
