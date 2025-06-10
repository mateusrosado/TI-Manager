<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/tema.css">
</head>
<body class="login-page">
  <div class="container">
   
    <div class="ball"></div>
  
    <div class="form-container">
      <h1>LOGIN ADM</h1>
      <form method="post" action="<?= BASE_URL; ?>/Login/loginAdm">
        <input type="text" placeholder="Código de acesso">
        <input type="password" placeholder="Senha">
          <a href="../SendEmail/">Esqueci minha senha</a>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
