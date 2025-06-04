<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresa</title> <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
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
        <a class="active" href="<?= BASE_URL; ?>Empresa/">Empresas</a> <a href="<?= BASE_URL; ?>Funcionario/">Funcionarios</a>
        <a href="<?= BASE_URL; ?>TableExample/">Historico</a>
        <a href="<?= BASE_URL; ?>Login/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="cadastro-empresa"> <form method="POST"> <h2>Cadastro de Empresa</h2>
                <label for="nome_empresa">Nome</label>
                <input type="text" id="nome_empresa" name="nome_empresa" placeholder="Nome da empresa">

                <label for="cnpj_empresa">CNPJ</label>
                <input type="text" id="cnpj_empresa" name="cnpj_empresa" placeholder="CNPJ da empresa">

                <label for="contato_empresa">Contato</label>
                <input type="text" id="contato_empresa" name="contato_empresa" placeholder="XXXX - XXXX">

                <label for="endereco_empresa">Endereço</label>
                <input type="text" id="endereco_empresa" name="endereco_empresa" placeholder="Endereço da empresa">

                <button class="btn" type="submit">Salvar</button>
            </form>
        </section>
    </main>
</body>

</html>