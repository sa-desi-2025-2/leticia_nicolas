

document.addEventListener("DOMContentLoaded", () => {

    const modal = document.getElementById("modalCategorias");
    const botaoAbrir = document.getElementById("abrirCategorias");
    const botaoSalvar = document.getElementById("salvarCategorias");
    const botaoFechar = document.getElementById("fecharModal");
    const form = document.getElementById("formCategorias");

    if (!modal || !form || !botaoSalvar || !botaoFechar) {
        console.warn("modal_categoria.js: elementos do modal não encontrados.");
        return;
    }


    try {
        if (typeof mostrarModalCategorias !== "undefined" && mostrarModalCategorias === true) {
            abrirModal();
        }
    } catch {}


    if (botaoAbrir) {
        botaoAbrir.addEventListener("click", (e) => {
            e.preventDefault();
            abrirModal();
        });
    }

  
    botaoSalvar.addEventListener("click", () => {
        const selecionadas = form.querySelectorAll(".checkbox-categoria:checked");

        if (selecionadas.length === 0) {
            alert("Selecione pelo menos uma categoria.");
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
                fecharModal();
                location.reload();
            } else {
                alert(res.mensagem);
            }
        })
        .catch(() => alert("Erro ao salvar categorias."));
    });



    botaoFechar.addEventListener("click", () => fecharModal());



    modal.addEventListener("click", (e) => {
        if (e.target === modal) fecharModal();
    });


    function abrirModal() {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }

    function fecharModal() {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }
});
