document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("postsContainer");
    if (!container) return;

    const userId = container.dataset.userId;

    fetch("carregar_posts.php?id_usuario=" + userId)
        .then(res => res.text())
        .then(html => container.innerHTML = html)
        .catch(() => container.innerHTML = "<p>Erro ao carregar posts.</p>");
});
