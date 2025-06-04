<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário</title> <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css">
</head>

<body class="home">
    <aside>
        <div class="menu-title">🔵MENU</div>
        <div class="user">
            <div class="user-logo">
                𖡌
            </div>
            <div class="user-type">
                Cliente
            </div>
        </div>
        <a href="<?= BASE_URL; ?>Home/">Home</a>
        <a href="<?= BASE_URL; ?>Empresa/">Empresas</a>
        <a class="active" href="<?= BASE_URL; ?>Funcionario/">Funcionarios</a> <a href="<?= BASE_URL; ?>TableExample/">Historico</a>
        <a href="<?= BASE_URL; ?>Login/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="cadastro-funcionario"> <form method="POST"> <h2>Cadastro de Funcionário</h2>
                <label for="nome_funcionario">Nome</label>
                <input type="text" id="nome_funcionario" name="nome_funcionario" placeholder="Nome do Funcionário">

                <label for="cpf_funcionario">CPF</label>
                <input type="text" id="cpf_funcionario" name="cpf_funcionario" placeholder="CPF do Funcionário">

                <label for="email_funcionario">Email</label>
                <input type="email" id="email_funcionario" name="email_funcionario" placeholder="Digite um email">

                <label for="funcao_funcionario">Função</label>
                <input type="text" id="funcao_funcionario" name="funcao_funcionario" placeholder="Função do funcionário">

                <label for="senha_funcionario">Senha</label>
                <input type="password" id="senha_funcionario" name="senha_funcionario" placeholder="Digite uma senha">

                <button class="btn" type="submit">Salvar</button>
            </form>
        </section>
    </main>
</body>

</html>