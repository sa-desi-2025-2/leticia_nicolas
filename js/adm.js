document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ JS carregado com sucesso.");

   
    const botoes = document.querySelectorAll(".btn-desativar, .btn-ativar");

    botoes.forEach((botao) => {
        botao.addEventListener("click", async () => {
            const idUsuario = botao.dataset.id;
            const status = botao.classList.contains("btn-desativar") ? 0 : 1;

          
            botao.disabled = true;

            console.log("🔹 Enviando requisição para alterar_status.php", { idUsuario, status });

            const formData = new FormData();
            formData.append("id", idUsuario);
            formData.append("status", status);

            try {
                const resposta = await fetch("alterar_status.php", { 
                    method: "POST",
                    body: formData,
                });

                if (!resposta.ok) throw new Error("Erro HTTP " + resposta.status);

                const data = await resposta.json();
                console.log("📦 Resposta recebida:", data);

                if (data.success) {
                  
                    if (status === 0) {
                        botao.textContent = "Ativar";
                        botao.classList.remove("btn-desativar");
                        botao.classList.add("btn-ativar");
                    } else {
                        botao.textContent = "Desativar";
                        botao.classList.remove("btn-ativar");
                        botao.classList.add("btn-desativar");
                    }
                } else {
                    alert("❌ Erro ao alterar status: " + (data.error || "Desconhecido"));
                }

            } catch (erro) {
                console.error("🚨 Falha ao enviar requisição:", erro);
                alert("Erro de conexão com o servidor.");
            } finally {
          
                botao.disabled = false;
            }
        });
    });
});
