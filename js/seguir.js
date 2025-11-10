document.addEventListener("DOMContentLoaded", () => {
  // Seleciona todos os botões de seguir/deixar de seguir
  const botoesSeguir = document.querySelectorAll(".follow-btn, .unfollow-btn");

  botoesSeguir.forEach((botao) => {
    botao.addEventListener("click", async (e) => {
      e.preventDefault();

      const idSeguido = botao.dataset.id;
      if (!idSeguido) return;

      try {
        const resposta = await fetch("seguir_acao.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "id_seguido=" + encodeURIComponent(idSeguido),
        });

        const dados = await resposta.json();

        if (dados.status === "ok") {
          // 🔹 Caso seja botão da página principal (seguir)
          if (botao.classList.contains("follow-btn")) {
            if (dados.seguindo) {
              botao.textContent = "Seguindo";
              botao.classList.add("seguindo");
            } else {
              botao.textContent = "Seguir";
              botao.classList.remove("seguindo");
            }
          }

          // 🔹 Caso seja botão da página de seguidos (unfollow)
          if (botao.classList.contains("unfollow-btn")) {
            if (!dados.seguindo) {
              // Remove o card visualmente
              const card = botao.closest(".user-card");
              if (card) card.remove();
            }
          }
        } else if (dados.mensagem) {
          alert(dados.mensagem);
        } else {
          alert("Erro ao processar a ação.");
        }
      } catch (erro) {
        console.error("Erro ao seguir:", erro);
        alert("Erro ao processar a ação. Tente novamente.");
      }
    });
  });
});