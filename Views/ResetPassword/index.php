<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title> <!-- Título mais específico -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <!-- Caminho para o tema.css usando BASE_URL -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css">
</head>

<body class="login-page"> <!-- Mantém a classe para os estilos de página de login/recuperação -->
    <div class="container">

        <div class="ball">
            <!-- Verifique se estas divs bg-half são necessárias ou apenas o :before e :after do .ball no tema.css -->
            <!-- <div class="bg-half bg-half-50"></div> -->
            <!-- <div class="bg-half bg-third"></div> -->
        </div>

        <div class="form-container">
            <h1>Redefinir Senha</h1> <!-- Título do formulário -->
            <form method="POST"> <!-- Adicionado method="POST" para envio de dados -->
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite sua nova senha">

                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a nova senha">

                <!-- O botão Atualizar deve ser do tipo submit para enviar o formulário -->
                <button class="btn" type="submit">Atualizar</button>
            </form>
        </div>
    </div>

    <!-- Nenhum script JS necessário para o layout básico desta página,
         assim como nas páginas de login e recuperação de senha, a menos que haja funcionalidades específicas
         que o exijam (ex: validação em tempo real complexa, etc.). -->

</body>

</html>