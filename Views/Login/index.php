<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
    <div class="container">

        <div class="ball"></div>

        <div class="form-container">
            <h1>LOGIN</h1>
            <form method="POST"> <input type="text" placeholder="Código de acesso" name="codigo_acesso"> <input type="password" placeholder="Senha" name="senha"> <a href="<?= BASE_URL; ?>SendEmail/">Esqueci minha senha</a>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>