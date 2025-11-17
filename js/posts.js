// ../js/posts.js
document.addEventListener("DOMContentLoaded", () => {
    // Abre modal quando o botão é clicado
    const btnPost = document.querySelector(".btn-post");
    const criarPostModal = new bootstrap.Modal(document.getElementById('criarPostModal'), {});
    if (btnPost) btnPost.addEventListener("click", () => criarPostModal.show());
  
    const form = document.getElementById("formCriarPost");
    const imagemInput = document.getElementById("imagemPost");
    const previewWrapper = document.getElementById("previewWrapper");
    const previewImage = document.getElementById("previewImage");
    const feedback = document.getElementById("postFeedback");
  
    // Preview da imagem
    imagemInput.addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (!file) {
        previewWrapper.style.display = 'none';
        previewImage.src = '#';
        return;
      }
      const reader = new FileReader();
      reader.onload = function (ev) {
        previewImage.src = ev.target.result;
        previewWrapper.style.display = 'block';
      }
      reader.readAsDataURL(file);
    });
  
    // Envio do post
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      btn.disabled = true;
      feedback.style.display = 'none';
  
      const fd = new FormData(form);
      try {
        const res = await fetch('criar_post.php', {
          method: 'POST',
          body: fd
        });
        const json = await res.json();
        if (json.sucesso) {
          feedback.style.display = 'block';
          feedback.className = 'me-auto text-success';
          feedback.textContent = 'Post publicado com sucesso!';
          form.reset();
          previewWrapper.style.display = 'none';
          // fecha modal após 1s e recarrega lista
          setTimeout(() => {
            criarPostModal.hide();
            feedback.style.display = 'none';
            carregarPosts(); // atualiza feed
          }, 800);
        } else {
          feedback.style.display = 'block';
          feedback.className = 'me-auto text-danger';
          feedback.textContent = json.mensagem || 'Erro ao publicar.';
        }
      } catch (err) {
        console.error(err);
        feedback.style.display = 'block';
        feedback.className = 'me-auto text-danger';
        feedback.textContent = 'Erro de rede. Tente novamente.';
      } finally {
        btn.disabled = false;
      }
    });
  
    // Função para construir card HTML do post
    function montarPostHtml(post) {
      // escape simples (já vêm escapados do servidor JSON mas só por precaução)
      const perfilUrl = `perfil_usuario.php?id=${post.id_usuario}`;
      const imagemHtml = post.imagem_postagem ? `<img src="../uploads/${post.imagem_postagem}" alt="" style="width:100%; border-radius:8px; margin-top:10px;">` : '';
      const minhaReacao = post.minha_reacao || null;
  
      return `
        <div class="result-section post-card" data-id="${post.id_postagem}">
          <div style="display:flex; align-items:center; gap:12px;">
            <a href="${perfilUrl}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
              <img src="${post.foto_perfil || '../uploads/default.png'}" alt="avatar" style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #00bfa5;">
              <div style="color:#fff;">
                <strong style="color:#00ffc3;">${escapeHtml(post.nome_usuario)}</strong><br>
                <small style="color:#ddd;">${escapeHtml(post.nome_categoria || '')}</small>
              </div>
            </a>
          </div>
  
          <div style="margin-top:12px; color:#fff; font-size:15px;">${escapeHtml(post.texto_postagem)}</div>
          ${imagemHtml}
  
          <div style="margin-top:10px; display:flex; align-items:center; gap:12px;">
            <button class="btn-like btn btn-sm" data-tipo="like" style="background:transparent; border:1px solid #00ffc3; color:#fff;">
              👍 <span class="count-like">${post.likes}</span>
            </button>
            <button class="btn-dislike btn btn-sm" data-tipo="dislike" style="background:transparent; border:1px solid #ff6b6b; color:#fff;">
              👎 <span class="count-dislike">${post.dislikes}</span>
            </button>
          </div>
        </div>
      `;
    }
  
    function escapeHtml(text){
      if (text === null || text === undefined) return '';
      return text.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }
  
    // Carrega posts via AJAX
    async function carregarPosts() {
      try {
        const res = await fetch('carregar_posts.php');
        const json = await res.json();
        const container = document.getElementById('postsContainer');
        container.innerHTML = '';
        if (!json || !Array.isArray(json.posts)) {
          container.innerHTML = '<p style="color:#fff;">Nenhum post encontrado.</p>';
          return;
        }
        json.posts.forEach(p => {
          const html = montarPostHtml(p);
          // create element
          const div = document.createElement('div');
          div.innerHTML = html;
          container.appendChild(div.firstElementChild);
        });
  
        // conecta eventos de reagir
        conectarEventosReacao();
      } catch (err) {
        console.error('Erro ao carregar posts', err);
      }
    }
  
    // Conectar eventos like/dislike
    function conectarEventosReacao() {
      const posts = document.querySelectorAll('.post-card');
      posts.forEach(card => {
        const idPost = card.getAttribute('data-id');
        const btnLike = card.querySelector('.btn-like');
        const btnDislike = card.querySelector('.btn-dislike');
  
        // Atualiza estilo se já reagiu (server já enviou counts e minha_reacao)
        // Listener
        if (btnLike) {
          btnLike.addEventListener('click', async () => {
            await enviarReacao(idPost, 'like', card);
          });
        }
        if (btnDislike) {
          btnDislike.addEventListener('click', async () => {
            await enviarReacao(idPost, 'dislike', card);
          });
        }
      });
    }
  
    // Envia reação para servidor
    async function enviarReacao(idPost, tipo, card) {
      try {
        const fd = new FormData();
        fd.append('id_postagem', idPost);
        fd.append('tipo_reacao', tipo);
  
        const res = await fetch('reagir.php', { method: 'POST', body: fd });
        const json = await res.json();
        if (json.sucesso) {
          // atualiza os contadores no card
          const likeEl = card.querySelector('.count-like');
          const dislikeEl = card.querySelector('.count-dislike');
          if (likeEl) likeEl.textContent = json.likes;
          if (dislikeEl) dislikeEl.textContent = json.dislikes;
  
          // opcional: destacar o botão ativo
          const btnLike = card.querySelector('.btn-like');
          const btnDislike = card.querySelector('.btn-dislike');
          if (json.minha_reacao === 'like') {
            btnLike.classList.add('active-reaction');
            btnDislike.classList.remove('active-reaction');
          } else if (json.minha_reacao === 'dislike') {
            btnDislike.classList.add('active-reaction');
            btnLike.classList.remove('active-reaction');
          } else {
            // nenhuma
            btnLike.classList.remove('active-reaction');
            btnDislike.classList.remove('active-reaction');
          }
        } else {
          alert(json.mensagem || 'Erro ao reagir.');
        }
      } catch (err) {
        console.error(err);
        alert('Erro de rede ao reagir. Tente novamente.');
      }
    }
  
    // estilo para reação ativa (pode ajustar via CSS no seu arquivo)
    const style = document.createElement('style');
    style.innerHTML = `
      .active-reaction { box-shadow: 0 0 8px rgba(255,255,255,0.15); transform:scale(1.03); }
    `;
    document.head.appendChild(style);
  
    // Inicial
    carregarPosts();
  });
  