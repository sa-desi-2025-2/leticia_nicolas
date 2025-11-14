// ===== MENU DO PERFIL ===== 
const userButton = document.getElementById('userButton');
const dropdownMenu = document.getElementById('dropdownMenu');

if (userButton && dropdownMenu) {
    userButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('show');
    });

    // Fecha o menu ao clicar fora
    window.addEventListener('click', (event) => {
        if (!userButton.contains(event.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
}

// ===== BOTÕES "VER MAIS" =====
document.addEventListener("DOMContentLoaded", () => {
    const botoesVerMais = document.querySelectorAll(".load-more-btn");

    botoesVerMais.forEach(btn => {
        // se for um link com href, deixamos o link (navegação/servidor-side pagination)
        if (btn.tagName.toLowerCase() === 'a' && btn.getAttribute('href')) {
            // não interceptamos: permite paginação pelo servidor (cada página = 10 itens)
            return;
        }

        btn.addEventListener("click", () => {
            const targetAttr = btn.getAttribute("data-target");
            // se data-target for um nome de param (ex: page_comunidade), assumimos comportamento server-side
            // mas se for uma classe real (user-list / community-list), manipulamos client-side
            const lista = document.querySelector(`.${targetAttr}`);
            if (!lista) return;

            const items = Array.from(lista.children);
            let mostrados = items.filter(i => i.style.display !== "none" && getComputedStyle(i).display !== "none").length;

            // Mostra próximos 10 (ajustado conforme pedido)
            let mostradosAgora = 0;
            for (let i = mostrados; i < items.length; i++) {
                if (mostradosAgora >= 10) break;
                items[i].style.display = "flex";
                mostradosAgora++;
            }

            // Se todos já estiverem visíveis, remove o botão
            const todosVisiveis = items.every(it => it.style.display !== "none" && getComputedStyle(it).display !== "none");
            if (todosVisiveis) btn.style.display = "none";
        });
    });
});

// ===== MODAL DE CATEGORIAS =====
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalCategorias");
    const salvar = document.getElementById("salvarCategorias");
    const fechar = document.getElementById("fecharModal");
    const form = document.getElementById("formCategorias");
    const botaoAbrirMenu = document.getElementById("abrirCategorias");

    if (!modal || !salvar || !fechar || !form) {
        return; // admin sem modal → evita erro
    }

    // ABRIR MODAL PELO MENU (admin e usuário)
    if (botaoAbrirMenu) {
        botaoAbrirMenu.addEventListener("click", (e) => {
            e.preventDefault();
            modal.style.display = "flex";
            document.body.style.overflow = "hidden";
        });
    }

    // SALVAR CATEGORIAS
salvar.addEventListener("click", () => {
    const selecionadas = form.querySelectorAll(".checkbox-categoria:checked");

    if (selecionadas.length === 0) {
        alert("Selecione pelo menos uma categoria antes de salvar.");
        return;
    }

    const dados = new FormData(form);

    fetch("salvar_categorias.php", {
        method: "POST",
        body: dados
    })
    .then(r => r.json())
    .then(res => {
        if (res.sucesso) {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
            location.reload();
        } else {
            alert(res.mensagem);
        }
    })
    .catch(() => alert("Erro ao salvar categorias."));
});


    // FECHAR MODAL
    fechar.addEventListener("click", () => {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    });
});
