document.addEventListener("DOMContentLoaded", () => {
    const botoes = document.querySelectorAll(".follow-btn");

    // 🔹 Ícones SVG inline (para evitar depender de imagens externas)
    const iconeSeguir = '➕';   // ou: '🤝', '👤+', '➕'
    const iconeSeguindo = '✅'; // ou: '✔️', '💚', '🟢'

    // 🔹 Função para aplicar estado do botão
    function atualizarBotao(btn, seguindo) {
        if (seguindo) {
            btn.innerHTML = `${iconeSeguindo} Seguindo`;
            btn.classList.add("ativo");
        } else {
            btn.innerHTML = `${iconeSeguir} Seguir`;
            btn.classList.remove("ativo");
        }
    }

    // 🔹 Verifica quem o usuário já segue ao carregar a página
    fetch("verificar_seguidos.php")
        .then(res => res.json())
        .then(data => {
            botoes.forEach(btn => {
                const id = btn.dataset.id;
                const tipo = btn.dataset.tipo;

                const jaSegue =
                    (tipo === "usuario" && data.usuarios.includes(id)) ||
                    (tipo === "comunidade" && data.comunidades.includes(id));

                atualizarBotao(btn, jaSegue);
            });
        })
        .catch(err => console.error("Erro ao verificar seguidos:", err));

    // 🔹 Ação de seguir/desseguir com efeito visual
    botoes.forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const tipo = btn.dataset.tipo;

            // Efeito rápido de clique
            btn.style.transform = "scale(0.95)";
            setTimeout(() => (btn.style.transform = "scale(1)"), 150);

            fetch("seguir.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}&tipo=${tipo}`
            })
            .then(res => res.text())
            .then(response => {
                if (response === "followed") {
                    atualizarBotao(btn, true);
                } else if (response === "unfollowed") {
                    atualizarBotao(btn, false);
                }
            })
            .catch(err => console.error("Erro ao seguir:", err));
        });
    });
});
