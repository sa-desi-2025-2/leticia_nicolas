// ===== MENU DO PERFIL =====
const userButton = document.getElementById('userButton');
const dropdownMenu = document.getElementById('dropdownMenu');

userButton.addEventListener('click', () => {
    dropdownMenu.classList.toggle('show');
});

// Fecha o menu ao clicar fora
window.addEventListener('click', (event) => {
    if (!userButton.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
});

// ===== BOTÕES "VER MAIS" =====
document.addEventListener("DOMContentLoaded", () => {
    const botoesVerMais = document.querySelectorAll(".load-more-btn");

    botoesVerMais.forEach(btn => {
        btn.addEventListener("click", () => {
            const targetClass = btn.getAttribute("data-target"); // user-list ou community-list
            const lista = document.querySelector(`.${targetClass}`);
            if (!lista) return;

            const items = lista.children;
            let mostrados = 0;

            // Conta quantos estão visíveis
            for (let i = 0; i < items.length; i++) {
                if (items[i].style.display !== "none") mostrados++;
            }

            // Mostra próximos 5
            let mostradosAgora = 0;
            for (let i = mostrados; i < items.length; i++) {
                if (mostradosAgora >= 5) break;
                items[i].style.display = "flex"; // ou "block" se preferir
                mostradosAgora++;
            }

            // Se todos já estiverem visíveis, remove o botão
            let todosVisiveis = true;
            for (let i = 0; i < items.length; i++) {
                if (items[i].style.display === "none") todosVisiveis = false;
            }
            if (todosVisiveis) btn.style.display = "none";
        });
    });
});
