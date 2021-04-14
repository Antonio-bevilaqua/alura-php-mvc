<?php include __DIR__ . '/../inicio-html.php'; ?>

    <form method="post" action="/salvar-curso">
        <?php if (isset($curso)): ?>
            <input type="hidden" name="id" value="<?= $curso->getId(); ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" name="descricao" value="<?php echo isset($curso) ? $curso->getDescricao() : ""; ?>" class="form-control">
        </div>
        <button class="btn btn-primary">Salvar</button>
    </form>

<?php include __DIR__ . '/../fim-html.php'; ?>