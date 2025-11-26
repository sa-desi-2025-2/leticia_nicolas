document.addEventListener('DOMContentLoaded', () => {
  const followBtn = document.querySelector('.follow-btn');
  const seguidoresSpan = document.querySelector('.contador-seguidores');
  const seguindoSpan = document.querySelector('.contador-seguindo');

  if (followBtn) {
      followBtn.addEventListener('click', async () => {
          const userId = followBtn.dataset.id;
          const tipo = followBtn.dataset.tipo;
          const seguindo = followBtn.classList.contains('seguindo');
          const action = seguindo ? 'unfollow' : 'follow';

          try {
              const resp = await fetch('../php/seguir_acao.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                  body: `id=${userId}&tipo=${tipo}&acao=${action}`
              });

              if (!resp.ok) throw new Error('Erro na requisição');

              const data = await resp.json();

              if (data.status === 'success') {
             
                  followBtn.classList.toggle('seguindo');
                  followBtn.textContent = seguindo ? 'Seguir' : 'Seguindo';

                  if (seguidoresSpan) {
                      let count = parseInt(seguidoresSpan.textContent) || 0;
                      seguidoresSpan.textContent = seguindo ? count - 1 : count + 1;
                  }
                  if (seguindoSpan) {
                      let count = parseInt(seguindoSpan.textContent) || 0;
                      seguindoSpan.textContent = seguindo ? count - 1 : count + 1;
                  }
              } else {
                  alert(data.message || 'Erro ao seguir/deixar de seguir');
              }
          } catch (err) {
              console.error(err);
              alert('Erro ao processar a ação');
          }
      });
  }
});
