document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("btnAbrirCriarComunidade");
    const modal = document.getElementById("modalCriarComunidade");
    const fechar = document.getElementById("fecharCriarComunidade");

    if (!btn) return;

    btn.addEventListener("click", () => {
        if (modal) modal.style.display = "flex";
    });

    if (fechar) {
        fechar.addEventListener("click", () => {
            if (modal) modal.style.display = "none";
        });
    }

    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.style.display = "none";
        });
    }
});
