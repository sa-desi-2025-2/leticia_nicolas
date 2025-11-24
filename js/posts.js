

document.addEventListener("DOMContentLoaded", () => {

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get("q")) {
    console.log("Pesquisa ativa — posts não serão carregados");
    return; 
}


    const btnPost = document.querySelector(".btn-post");
    const modalElement = document.getElementById('criarPostModal');
    const criarPostModal = modalElement ? new bootstrap.Modal(modalElement, {}) : null;

    if (btnPost && criarPostModal) {
        btnPost.addEventListener("click", () => criarPostModal.show());
    }

    const form = document.getElementById("formCriarPost");
    const imagemInput = document.getElementById("imagemPost");
    const previewWrapper = document.getElementById("previewWrapper");
    const previewImage = document.getElementById("previewImage");
    const feedback = document.getElementById("postFeedback");

    // Preview da imagem
    if (imagemInput) {
        imagemInput.addEventListener("change", (e) => {
            const file = e.target.files[0];
            if (!file) {
                if (previewWrapper) previewWrapper.style.display = 'none';
                if (previewImage) previewImage.src = '#';
                return;
            }
            const reader = new FileReader();
            reader.onload = (ev) => {
                if (previewImage) previewImage.src = ev.target.result;
                if (previewWrapper) previewWrapper.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;
            if (feedback) feedback.style.display = 'none';

            const fd = new FormData(form);

            try {
                const res = await fetch('criar_post.php', {
                    method: 'POST',
                    body: fd
                });
                const json = await res.json();

                if (json.sucesso) {
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.className = 'me-auto text-success';
                        feedback.textContent = 'Post publicado com sucesso!';
                    }

                    form.reset();
                    if (previewWrapper) previewWrapper.style.display = 'none';

                    setTimeout(() => {
                        if (criarPostModal) criarPostModal.hide();
                        if (feedback) feedback.style.display = 'none';
                        carregarPostsPerfil(); 
                    }, 800);

                } else {
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.className = 'me-auto text-danger';
                        feedback.textContent = json.mensagem || 'Erro ao publicar.';
                    }
                }

            } catch (err) {
                console.error(err);
                if (feedback) {
                    feedback.style.display = 'block';
                    feedback.className = 'me-auto text-danger';
                    feedback.textContent = 'Erro de rede. Tente novamente.';
                }
            } finally {
                if (btn) btn.disabled = false;
            }
        });
    }



    function montarPostHtml(post) {
        const perfilUrl = `perfil_usuario.php?id=${post.id_usuario}`;
        const imagemHtml = post.imagem_postagem
          ? `<img src="../uploads/${post.imagem_postagem}" alt="" style="width:100%; border-radius:8px; margin-top:10px;">`
          : '';

        return `
        <div class="result-section post-card" data-id="${post.id_postagem}">
          <div style="display:flex; align-items:center; gap:12px;">
            <a href="${perfilUrl}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
              <img src="${post.foto_perfil || '../uploads/default.png'}" 
                   alt="avatar" 
                   style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #00bfa5;">
              <div style="color:#fff;">
                <strong style="color:#00ffc3;">${escapeHtml(post.nome_usuario)}</strong><br>
                <small style="color:#ddd;">${escapeHtml(post.nome_categoria || '')}</small>
              </div>
            </a>
          </div>

          <div style="margin-top:12px; color:#fff; font-size:15px;">
            ${escapeHtml(post.texto_postagem)}
          </div>

          ${imagemHtml}

          <div style="margin-top:10px; display:flex; align-items:center; gap:12px;">
            <button class="btn-like btn btn-sm" data-tipo="like" 
                    style="background:transparent; border:1px solid #00ffc3; color:#fff;">
              👍 <span class="count-like">${post.likes}</span>
            </button>

            <button class="btn-dislike btn btn-sm" data-tipo="dislike" 
                    style="background:transparent; border:1px solid #ff6b6b; color:#fff;">
              👎 <span class="count-dislike">${post.dislikes}</span>
            </button>
          </div>
        </div>`;
    }

    function escapeHtml(text) {
        if (!text) return "";
        return text
            .replace(/&/g,"&amp;")
            .replace(/</g,"&lt;")
            .replace(/>/g,"&gt;")
            .replace(/"/g,"&quot;")
            .replace(/'/g,"&#039;");
    }


    async function carregarPostsPerfil() {
        try {
            const res = await fetch('carregar_posts.php?id_usuario=<?= $idUsuarioPerfil ?>');
            const json = await res.json();
            const container = document.getElementById('postsContainer');

            container.innerHTML = '';

            if (!json || !Array.isArray(json.posts) || json.posts.length === 0) {
                container.innerHTML = '<p style="color:white;">Nenhum post encontrado.</p>';
                return;
            }

            json.posts.forEach(post => {
                const el = document.createElement("div");
                el.innerHTML = montarPostHtml(post);
                container.appendChild(el.firstElementChild);
            });

            conectarEventosReacao();

        } catch (error) {
            console.error("Erro ao carregar posts do perfil:", error);
        }
    }



    function conectarEventosReacao() {
        const cards = document.querySelectorAll('.post-card');

        cards.forEach(card => {
            const id = card.getAttribute('data-id');
            const like = card.querySelector('.btn-like');
            const dislike = card.querySelector('.btn-dislike');

            if (like) like.addEventListener("click", () => reagir(id, "like", card));
            if (dislike) dislike.addEventListener("click", () => reagir(id, "dislike", card));
        });
    }

    async function reagir(id, tipo, card) {
        try {
            const fd = new FormData();
            fd.append("id_postagem", id);
            fd.append("tipo_reacao", tipo);

            const res = await fetch("reagir.php", { method: "POST", body: fd });
            const json = await res.json();

            if (json.sucesso) {
                card.querySelector(".count-like").textContent = json.likes;
                card.querySelector(".count-dislike").textContent = json.dislikes;
            }
        } catch (error) {
            console.error("Erro ao reagir:", error);
        }
    }


    const style = document.createElement("style");
    style.innerHTML = `
        .active-reaction {
            box-shadow: 0 0 8px rgba(255,255,255,0.15);
            transform: scale(1.03);
        }
    `;
    document.head.appendChild(style);


    carregarPostsPerfil();
});

