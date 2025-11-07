document.addEventListener("DOMContentLoaded", () => {
    // Seleciona todos os botoes de seguir/deixar de seguir
    const botoesSeguir = document.querySelectorAll(".follow-btn, .unfollow-btn");
  
    botoesSeguir.forEach((botao) => {
      botao.addEventListener("click", async (e) => {
        e.preventDefault();
  
        const idSeguido = botao.dataset.id;
  
        // Envia a requisição via fetch
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
            // Alterna o texto do botão conforme o retorno
            if (dados.seguindo) {
              botao.textContent = "Seguindo";
              botao.classList.add("seguindo");
            } else {
              botao.textContent = "Seguir";
              botao.classList.remove("seguindo");
            }
          } else if (dados.mensagem) {
            alert(dados.mensagem);
          }
        } catch (erro) {
          console.error("Erro ao seguir:", erro);
          alert("Erro ao processar ação. Tente novamente.");
        }
      });
    });
  });
  