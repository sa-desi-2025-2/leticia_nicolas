document.addEventListener('DOMContentLoaded', () => {
    const followBtn = document.querySelector('.follow-btn');
    if (followBtn) {
      followBtn.addEventListener('click', async () => {
        const userId = followBtn.dataset.id;
        const tipo = followBtn.dataset.tipo;
        const seguindo = followBtn.classList.contains('seguindo');
        const action = seguindo ? 'unfollow' : 'follow';
  
        const resp = await fetch('../php/seguir_acao.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${userId}&tipo=${tipo}&acao=${action}`
        });
  
        if (resp.ok) {
          followBtn.classList.toggle('seguindo');
          followBtn.textContent = seguindo ? 'Seguir' : 'Seguindo';
        }
      });
    }
  });
  