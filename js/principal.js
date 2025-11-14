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
    const abrirCategoriasLink = document.getElementById('abrirCategorias'); // link no menu
    const modal = document.getElementById('modalCategorias');
    const btnSalvar = document.getElementById('salvarCategorias');
    const btnFechar = document.getElementById('fecharModal');
    const form = document.getElementById('formCategorias');

    let salvarExecutado = false; // marca se salvou com sucesso

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

    // verifica se há ao menos um checkbox marcado
    function temAlgumaMarcada() {
        if (!modal) return false;
        const marcadas = modal.querySelectorAll('.checkbox-categoria:checked');
        return marcadas.length > 0;
    }

    // lógica de fechamento forçado (não fecha, apenas avisa) — usuario precisa salvar
    function tentarFecharSemSalvar() {
        if (!temAlgumaMarcada()) {
            alert('Selecione pelo menos uma categoria antes de fechar!');
            return false;
        }
        alert('Você marcou categorias — clique em "Salvar" para confirmar suas preferências antes de fechar.');
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

    // fechar pelo botão: só se já salvou
    if (btnFechar) {
        btnFechar.addEventListener('click', (e) => {
            e.preventDefault();
            // só permite fechar se já salvou (salvarExecutado true)
            if (salvarExecutado) {
                if (modal) modal.style.display = 'none';
                bloquearPagina(false);
            } else {
                // impedir fechar sem salvar; se ao menos marcou, pedir salvar; senão pedir seleção
                tentarFecharSemSalvar();
            }
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
                    salvarExecutado = true;
                    alert('Categorias salvas com sucesso!');
                    // fecha modal e desbloqueia página
                    if (modal) modal.style.display = 'none';
                    bloquearPagina(false);
                    // recarregar para atualizar estado (mantém compatibilidade com todo o restante)
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
                if (!temAlgumaMarcada()) {
                    alert('Selecione pelo menos uma categoria antes de fechar!');
                    return;
                } else {
                    alert('Clique em "Salvar" para confirmar suas preferências antes de fechar.');
                    return;
                }
            }
        });

        // evita que clicks dentro do conteúdo fechem algo (segurança)
        const inner = modal.querySelector('.modal-categorias');
        if (inner) {
            inner.addEventListener('click', (ev) => {
                ev.stopPropagation();
            });
        }
    }
});
