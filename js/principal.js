
const userButton = document.getElementById('userButton');
const dropdownMenu = document.getElementById('dropdownMenu');

if (userButton && dropdownMenu) {
    userButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('show');
    });

    window.addEventListener('click', (event) => {
        if (!userButton.contains(event.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
}


document.addEventListener("DOMContentLoaded", () => {
    const botoesVerMais = document.querySelectorAll(".load-more-btn");

    botoesVerMais.forEach(btn => {
        if (btn.tagName.toLowerCase() === 'a' && btn.getAttribute('href')) {
            return;
        }

        btn.addEventListener("click", () => {
            const targetAttr = btn.getAttribute("data-target");
            const lista = document.querySelector(`.${targetAttr}`);
            if (!lista) return;

            const items = Array.from(lista.children);
            let mostrados = items.filter(i => i.style.display !== "none" && getComputedStyle(i).display !== "none").length;

            let mostradosAgora = 0;
            for (let i = mostrados; i < items.length; i++) {
                if (mostradosAgora >= 10) break;
                items[i].style.display = "flex";
                mostradosAgora++;
            }

            const todosVisiveis = items.every(it => it.style.display !== "none" && getComputedStyle(it).display !== "none");
            if (todosVisiveis) btn.style.display = "none";
        });
    });
});
