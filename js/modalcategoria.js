document.getElementById("abrirCategorias")?.addEventListener("click", (e) => {
    e.preventDefault();
    document.getElementById("modalCategorias").style.display = "flex";
});
document.getElementById("fecharModal")?.addEventListener("click", () => {
    document.getElementById("modalCategorias").style.display = "none";
});