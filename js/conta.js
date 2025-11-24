
const fotoPerfilInput = document.getElementById('foto_perfil');
const previewPerfil = document.getElementById('previewPerfil');

if(fotoPerfilInput && previewPerfil){
    fotoPerfilInput.addEventListener('change', function() {
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                previewPerfil.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
}


const fotoBannerInput = document.getElementById('foto_banner');
const previewBanner = document.getElementById('previewBanner');

if(fotoBannerInput && previewBanner){
    fotoBannerInput.addEventListener('change', function() {
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                previewBanner.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
}
