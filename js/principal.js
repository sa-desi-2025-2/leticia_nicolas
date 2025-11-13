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
            const targetClass = btn.getAttribute("data-target"); // user-list ou community-list
            const lista = document.querySelector(`.${targetClass}`);
            if (!lista) return;

            const items = lista.children;
            let mostrados = 0;

            // Conta quantos estão visíveis
            for (let i = 0; i < items.length; i++) {
                if (items[i].style.display !== "none") mostrados++;
            }

            // Mostra próximos 10 (ajustado conforme pedido)
            let mostradosAgora = 0;
            for (let i = mostrados; i < items.length; i++) {
                if (mostradosAgora >= 10) break;
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

// ===== MODAL DE CATEGORIAS =====
document.addEventListener("DOMContentLoaded", () => {
    const abrirCategoriasLink = document.getElementById('abrirCategorias'); // link no menu
    const modal = document.getElementById('modalCategorias');
    const btnSalvar = document.getElementById('salvarCategorias');
    const btnFechar = document.getElementById('fecharModal');
    const form = document.getElementById('formCategorias');

    // util: bloqueia/desbloqueia scroll da página
    function bloquearPagina(b) {
        document.body.style.overflow = b ? 'hidden' : '';
    }

    // abre modal e bloqueia interação
    function abrirModal() {
        if (!modal) return;
        modal.style.display = 'flex';
        bloquearPagina(true);
    }

    // tenta fechar modal: só permite fechar se o usuário já tiver salvo ao menos uma categoria
    // implementamos lógica em que o botão FECHAR SEMPRE verifica se há checkbox marcada E se já foi salvo (server)
    // Para simplificar confiamos no fato de que salvar devolve sucesso; após salvar, o modal poderá fechar.
    function fecharModalForcado() {
        if (!modal) return;
        // verifica se existe ao menos uma checkbox marcada
        const marcadas = modal.querySelectorAll('.checkbox-categoria:checked');
        if (marcadas.length === 0) {
            alert('Selecione pelo menos uma categoria antes de fechar!');
            return false;
        }
        // Se chegou aqui significa que o usuário marcou ao menos uma — entretanto, ele precisa salvar.
        // Para garantir, solicitamos que ele clique em "Salvar" (não fechamos automaticamente aqui).
        alert('Clique em "Salvar" para confirmar suas preferências antes de fechar.');
        return false;
    }

    if (abrirCategoriasLink && modal) {
        abrirCategoriasLink.addEventListener('click', (e) => {
            e.preventDefault();
            abrirModal();
        });
    }

    // Se modal existir e estiver visível ao carregar (usuário sem categorias), bloqueia a página
    if (modal && getComputedStyle(modal).display !== 'none') {
        bloquearPagina(true);
    }

    // fechar pelo botão: mas só se ao menos uma categoria estiver marcada E após salvar (salvar deve ser clicado)
    if (btnFechar) {
        btnFechar.addEventListener('click', (e) => {
            e.preventDefault();
            // impedir fechar sem seleção + salvar
            fecharModalForcado();
        });
    }

    // salvar via AJAX
    if (btnSalvar && form) {
        btnSalvar.addEventListener('click', (e) => {
            e.preventDefault();

            // coleta selecionadas
            const selecionadas = Array.from(form.querySelectorAll('.checkbox-categoria:checked')).map(c => c.value);

            if (selecionadas.length === 0) {
                alert('Selecione pelo menos uma categoria antes de salvar.');
                return;
            }

            // montar body x-www-form-urlencoded (compatível com seu salvar_categorias.php)
            const params = new URLSearchParams();
            selecionadas.forEach(id => params.append('categorias[]', id));

            fetch('salvar_categorias.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params.toString()
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.sucesso) {
                    alert('Categorias salvas com sucesso!');
                    // fecha modal e desbloqueia página
                    if (modal) modal.style.display = 'none';
                    bloquearPagina(false);
                    // recarregar para atualizar estado (opcional, mantém compatibilidade com resto)
                    location.reload();
                } else {
                    alert(data.mensagem || 'Erro ao salvar categorias.');
                }
            })
            .catch(() => {
                alert('Erro de comunicação com o servidor.');
            });
        });
    }

    // impedir fechar clicando fora enquanto modal estiver bloqueando
    if (modal) {
        modal.addEventListener('click', (e) => {
            // se clicar na overlay (fora do conteúdo), tratamos como tentativa de fechar: prevenir se não houver seleção/salvo
            if (e.target === modal) {
                // se ao menos uma marcada, pedimos salvar; caso contrário alert.
                const marcadas = modal.querySelectorAll('.checkbox-categoria:checked');
                if (marcadas.length === 0) {
                    alert('Selecione pelo menos uma categoria antes de fechar!');
                    return;
                } else {
                    alert('Clique em "Salvar" para confirmar suas preferências antes de fechar.');
                    return;
                }
            }
        });
    }
});
