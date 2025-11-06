// Dropdown do perfil
const userButton = document.getElementById('userButton');
const dropdownMenu = document.getElementById('dropdownMenu');
if (userButton) {
  userButton.addEventListener('click', () => {
    dropdownMenu.classList.toggle('show');
  });

  window.addEventListener('click', (event) => {
    if (!userButton.contains(event.target)) {
      dropdownMenu.classList.remove('show');
    }
  });
}

// Modal de criação de post
const btnPost = document.querySelector('.btn-post');
const postModal = document.getElementById('postModal');
const closeModal = document.getElementById('closeModal');
const cancelModal = document.getElementById('cancelModal');

btnPost.addEventListener('click', () => {
  postModal.classList.add('show');
});

closeModal.addEventListener('click', () => {
  postModal.classList.remove('show');
});

cancelModal.addEventListener('click', () => {
  postModal.classList.remove('show');
});

// Criar post
const postForm = document.getElementById('postForm');
const postsContainer = document.getElementById('postsContainer');

postForm.addEventListener('submit', (e) => {
  e.preventDefault();

  const titulo = document.getElementById('titulo').value;
  const texto = document.getElementById('texto').value;
  const imagemInput = document.getElementById('imagem');
  let imagemURL = '';

  if (imagemInput.files && imagemInput.files[0]) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imagemURL = e.target.result;
      adicionarPost(titulo, texto, imagemURL);
    };
    reader.readAsDataURL(imagemInput.files[0]);
  } else {
    adicionarPost(titulo, texto, '');
  }

  postModal.classList.remove('show');
  postForm.reset();
});

function adicionarPost(titulo, texto, imagemURL) {
  const post = document.createElement('div');
  post.classList.add('post-card');
  post.innerHTML = `
    <h3>${titulo}</h3>
    ${imagemURL ? `<img src="${imagemURL}" class="post-img">` : ''}
    <p>${texto}</p>
  `;
  postsContainer.prepend(post);
}

