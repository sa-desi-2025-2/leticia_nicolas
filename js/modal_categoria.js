// apenas abre o modal (fechamento e salvar são tratados em principal.js)
document.getElementById("abrirCategorias")?.addEventListener("click", (e) => {
    e.preventDefault();
    const modal = document.getElementById("modalCategorias");
    if (modal) {
        modal.style.display = "flex";
        // bloquear scroll (principal.js também faz, mas colocamos aqui para caso siga outro fluxo)
        document.body.style.overflow = 'hidden';
    }
});
