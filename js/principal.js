        // Mostrar ou esconder o menu do perfil
        const userButton = document.getElementById('userButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

        // Fecha o menu ao clicar fora
        window.addEventListener('click', (event) => {
            if (!userButton.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });