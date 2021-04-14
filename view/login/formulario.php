<?php include __DIR__ . '/../inicio-html.php'; ?>

    <form method="post" action="/realiza-login">
        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" id="email" type="text" class="form-control" placeholder="Seu Email"> 
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input name="senha" id="senha" type="password" class="form-control" placeholder="seu primeiro e segundo nome"> 
        </div>
        <button class="btn btn-primary">Entrar</button>
    </form>

<?php include __DIR__ . '/../fim-html.php'; ?>