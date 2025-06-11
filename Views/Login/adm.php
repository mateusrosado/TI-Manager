<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - TI Manager</title>
  <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/tema.css">
</head>
<body class="login-page">
  <div class="container">
  
    <div class="ball"></div>
 
    <div class="form-container">
      <h1>LOGIN ADM</h1>
        <?php
        if (isset($_SESSION['login_error']) && !empty($_SESSION['login_error'])):
            $error = $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        ?>
            <div style="color: #ffcccc; background-color: #a00; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 1.4rem;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
      <form method="post" action="<?= BASE_URL; ?>Adm/login">
        <input type="text" name="cnpj" placeholder="Digite o seu CNPJ" required>
        <input type="password" name="password" placeholder="Digite a sua Senha" required>
          <a href="<?= BASE_URL; ?>index.php?url=SendEmail/">Esqueci minha senha</a>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
