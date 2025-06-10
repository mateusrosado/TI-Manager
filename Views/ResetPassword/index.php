<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css">
</head>

<body class="login-page">
    <div class="container">

        <div class="ball">
        </div>

        <div class="form-container">
            <h1>Redefinir Senha</h1>
            <form method="POST">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite sua nova senha">

                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a nova senha">

                <button class="btn" type="submit">Atualizar</button>
            </form>
        </div>
    </div>

</body>

</html>