document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formCriarComunidade");
    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const feedback = document.getElementById("feedbackComunidade");
        feedback.style.display = "none";

        let dados = new FormData(this);

        fetch("criar_comunidade.php", {
            method: "POST",
            body: dados,
            credentials: "same-origin"
        })
        .then(r => r.json())
        .then(res => {

            if (res.erro) {
                feedback.style.display = "block";
                feedback.style.color = "red";
                feedback.textContent = res.erro;
                return;
            }

            if (res.sucesso) {
                feedback.style.display = "block";
                feedback.style.color = "green";
                feedback.textContent = "Comunidade criada com sucesso!";
            } else {
                feedback.style.display = "block";
                feedback.style.color = "red";
                feedback.textContent = "Erro desconhecido ao criar!";
                return;
            }

            // Adicionar ao sidebar (elemento dentro do include sidebar_comunidades.php)
            const sidebar = document.querySelector(".menu-comunidades");
            if (sidebar) {
                const novo = document.createElement("a");
                novo.href = `comunidade.php?id=${res.id}`;
                novo.classList.add("comunidade-icone");
                const img = document.createElement("img");
                img.src = res.imagem ? `../uploads/${res.imagem}` : `../img/default_comunidade.png`;
                img.alt = res.nome || 'Comunidade';
                novo.appendChild(img);
                sidebar.appendChild(novo);
            }

            // Resetar formulario
            form.reset();

            // Fechar modal depois de um curto delay
            setTimeout(() => {
                const modal = document.getElementById("modalCriarComunidade");
                if (modal) modal.style.display = "none";
                feedback.style.display = "none";
            }, 900);

        })
        .catch(err => {
            feedback.style.display = "block";
            feedback.style.color = "red";
            feedback.textContent = "Erro na requisição: " + err.message;
        });
    });

});
