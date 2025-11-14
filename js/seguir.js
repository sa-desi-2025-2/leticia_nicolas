document.addEventListener("DOMContentLoaded", () => {
  const botoesSeguir = document.querySelectorAll(".follow-btn, .unfollow-btn");

  botoesSeguir.forEach((botao) => {
    botao.addEventListener("click", async (e) => {
      e.preventDefault();

      const idSeguido = botao.dataset.id;
      const tipo = botao.dataset.tipo || "usuario";

      if (!idSeguido) return;
      if (botao.disabled) return;

      botao.disabled = true;

      try {
        const resposta = await fetch("seguir_acao.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body:
            "id_seguido=" +
            encodeURIComponent(idSeguido) +
            "&tipo=" +
            encodeURIComponent(tipo),
        });

        const dados = await resposta.json();

        if (dados.status === "ok") {
          // Se passou a seguir
          if (dados.seguindo) {
            botao.textContent = "Deixar de seguir";
            botao.classList.remove("follow-btn");
            botao.classList.add("unfollow-btn");
          } 
          // Se deixou de seguir
          else {
            botao.textContent = "Seguir";
            botao.classList.remove("unfollow-btn");
            botao.classList.add("follow-btn");
          }
        } else {
          alert(dados.mensagem || "Erro ao processar a ação.");
        }
      } catch (erro) {
        console.error("Erro ao seguir:", erro);
        alert("Erro ao processar a ação. Tente novamente.");
      } finally {
        botao.disabled = false;
      }
    });
  });
});
