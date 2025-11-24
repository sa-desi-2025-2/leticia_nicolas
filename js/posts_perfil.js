document.addEventListener("click", function(e) {


    if (e.target.classList.contains("btn-excluir")) {
        const idPost = e.target.dataset.id;

        if (!confirm("Deseja realmente excluir este post?")) return;

        fetch("excluir_postagem.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id_postagem=" + idPost
        })
        .then(res => res.text())
        .then(resposta => {
            if (resposta.trim() === "ok") {
                e.target.closest(".post").remove();
            } else {
                alert("Erro ao excluir post: " + resposta);
            }
        });
    }

  
    if (e.target.classList.contains("btn-editar")) {
        const idPost = e.target.dataset.id;
        const postDiv = e.target.closest(".post");
        const textoEl = postDiv.querySelector(".post-texto");

        const textoAtual = textoEl.innerText;
        const novoTexto = prompt("Edite seu post:", textoAtual);

        if (novoTexto === null || novoTexto.trim() === "") return;

        fetch("editar_postagem.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id_postagem=" + idPost + "&texto_postagem=" + encodeURIComponent(novoTexto)
        })
        .then(res => res.text())
        .then(resposta => {
            if (resposta.trim() === "ok") {
                textoEl.innerText = novoTexto;
            } else {
                alert("Erro ao editar post: " + resposta);
            }
        });
    }

});
