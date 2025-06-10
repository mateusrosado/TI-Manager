<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Empresas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
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
        <a class="active" href="<?= BASE_URL; ?>Home/">Home</a>
        <a href="<?= BASE_URL; ?>Empresa/">Empresas</a>
        <a href="<?= BASE_URL; ?>Funcionario/">Funcionarios</a>
        <a href="<?= BASE_URL; ?>TableExample/">Historico</a>
        <a href="<?= BASE_URL; ?>Login/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="home" class="active">
            <div class="header-section">
                <h2>Empresas e Chamados</h2>
                <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>AddCliente/'">+ Nova Empresa</button>
            </div>
            <div class="card-container">
                <div class="card">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Dell_Logo.svg/512px-Dell_Logo.svg.png" alt="Dell" width="60">
                    <h3>Dell Brasil</h3>
                    <p><strong>08</strong> Funcionários</p>
                    <p><strong>04</strong> Chamados</p>
                    <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>TableExample/'">Ver mais</button>
                </div>
                <div class="card">
                    <img src="https://www.intel.com.br/content/dam/logos/intel-header-logo.svg" alt="Intel" width="60">
                    <h3>Intel Brasil</h3>
                    <p><strong>06</strong> Funcionários</p>
                    <p><strong>02</strong> Chamados</p>
                    <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>TableExample/'">Ver mais</button>
                </div>
            </div>
        </section>
    </main>

    </body>
</html>