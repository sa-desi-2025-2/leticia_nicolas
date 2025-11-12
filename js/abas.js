// abas.js

// Aguarda o DOM carregar antes de executar
document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".tab-link");
  const tabs = document.querySelectorAll(".tab-content");

  // Alternar abas (Configurações, Conta, etc.)
  links.forEach(link => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const target = link.getAttribute("data-tab");

      // Remove "active" de todas as abas
      tabs.forEach(tab => tab.classList.remove("active"));
      // Mostra apenas a aba clicada
      document.getElementById(target).classList.add("active");

      // Atualiza o estado visual do link ativo
      links.forEach(l => l.classList.remove("active"));
      link.classList.add("active");
    });
  });
});
