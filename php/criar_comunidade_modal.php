<?php
// Este arquivo contém apenas o modal manual (neumorphism ou seu estilo).
// Deve ser incluído em pagina_principal.php apenas uma vez.
?>
<div id="modalCriarComunidade" class="modal-comunidade" style="display:none;">
    <div class="modal-conteudo">
        <h2>Criar comunidade</h2>

        <form id="formCriarComunidade" method="POST" enctype="multipart/form-data">
            <label>Nome da comunidade</label>
            <input type="text" name="nome_comunidade" required>

            <label>Descrição</label>
            <textarea name="descricao_comunidade" rows="3" required></textarea>

            <div class="form-group check-maior-idade">
            <label>
                <input type="checkbox" name="maior_idade" value="1">
                Comunidade para maiores de 18 anos
            </label>
            </div>

            <label>Categoria</label>
            <select name="id_categoria" required>
                <option value="">Selecione</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= (int)$cat['id_categoria'] ?>">
                        <?= htmlspecialchars($cat['nome_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Imagem (opcional)</label>
            <input type="file" name="imagem_comunidade" accept="image/*">

            <div id="feedbackComunidade" style="margin:10px 0; display:none;"></div>

            <div class="botoes">
                <button type="button" id="fecharCriarComunidade">Cancelar</button>
                <button type="submit" class="btn-salvar">Criar</button>
            </div>
        </form>
    </div>
</div>
