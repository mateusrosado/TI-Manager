<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Funcionário</title>
  <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/tema.css">
</head>
<body class="login-page">
  <div class="container">

    <div class="ball"></div>

    <div class="form-container">
      <h1>LOGIN FUNCIONÁRIO</h1>
      <?php
      if (isset($_SESSION['login_error']) && !empty($_SESSION['login_error'])):
        $error = $_SESSION['login_error'];
        unset($_SESSION['login_error']);
      ?>
        <div style="color: #ffcccc; background-color: #a00; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 1.4rem;">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>
      <form method="post" action="<?= BASE_URL; ?>Funcionario/login">
        <input type="email" name="email" placeholder="Digite o seu Email" required>
        <input type="password" name="password" placeholder="Digite a sua Senha" required>
        <a href="<?= BASE_URL; ?>index.php?url=SendEmail/" class="text-blue-500 hover:underline mt-2 block text-sm text-center">Esqueci minha senha</a>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
