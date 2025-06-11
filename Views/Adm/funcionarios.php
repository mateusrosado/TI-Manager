<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Funcionários</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css" />
</head>

<body class="home">
    <aside>
        <div class="menu-title">🔵MENU</div>
        <div class="user">
            <div class="user-logo">𖡌</div>
            <?= htmlspecialchars($viewData['name'] ?? 'Usuário'); ?> (<?= htmlspecialchars($viewData['user_role'] ?? 'Admin'); ?>)
        </div>
        <a href="<?= BASE_URL; ?>Adm/home">Home</a>
        <a href="<?= BASE_URL; ?>Adm/empresas">Empresas</a>
        <a class="active" href="<?= BASE_URL; ?>Adm/funcionarios">Funcionarios</a>
        <a href="<?= BASE_URL; ?>Adm/historico">Historico</a>
        <a href="<?= BASE_URL; ?>Adm/logout/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="funcionarios">
            <div class="header-section">
                <h2>Funcionarios</h2>
                <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>AddFuncionario/'">+ Novo Funcionario</button>
            </div>
            <div class="container-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Função</th>
                            <th>Atribuído a</th>
                            <th>Data de Registro</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Joao Carlos</td>
                            <td>Tecnico de TI</td>
                            <td>Dell Brasil</td>
                            <td>22/05/25</td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Funcionario 1</td>
                            <td>Tecnico de TI</td>
                            <td>Empresa A</td>
                            <td>22/05/25</td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Funcionario 2</td>
                            <td>Tecnico de TI</td>
                            <td>Empresa B</td>
                            <td>22/05/25</td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>004</td>
                            <td>Funcionario 3</td>
                            <td>Tecnico de TI</td>
                            <td>Empresa C</td>
                            <td>22/05/25</td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>005</td>
                            <td>Funcionario 4</td>
                            <td>Tecnico de TI</td>
                            <td>Empresa D</td>
                            <td>22/05/25</td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>006</td>
                            <td>Funcionario 5</td>
                            <td>Tecnico de TI</td>
                            <td>Empresa E</td>
                            <td>22/05/25</td>
                            <td>✎</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>