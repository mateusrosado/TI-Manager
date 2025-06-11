<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Empresas</title> <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
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
        <a class="active" href="<?= BASE_URL; ?>Adm/empresas">Empresas</a>
        <a href="<?= BASE_URL; ?>Adm/funcionarios">Funcionarios</a>
        <a href="<?= BASE_URL; ?>Adm/historico">Historico</a>
        <a href="<?= BASE_URL; ?>Adm/logout/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="empresas"> <div class="header-section">
                <h2>Empresas</h2>
                <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>AddCliente/'">+ Nova Empresa</button>
            </div>
            <div class="container-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Empresa</th>
                            <th>Chamado</th>
                            <th>Funcionario</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Dell Brasil</td>
                            <td>Problema de Rede</td>
                            <td>Funcionario 05</td>
                            <td>22/05/25</td>
                            <td><span class="status">Aberto</span></td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Intel</td>
                            <td>Erro no Software</td>
                            <td>Funcionario 02</td>
                            <td>22/05/25</td>
                            <td><span class="status">Aberto</span></td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>NVIDIA</td>
                            <td>Manutenção</td>
                            <td>Funcionario 03</td>
                            <td>22/05/25</td>
                            <td><span class="status">Aberto</span></td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>004</td>
                            <td>Lenovo</td>
                            <td>Problema de Rede</td>
                            <td>Funcionario 01</td>
                            <td>22/05/25</td>
                            <td><span class="status">Aberto</span></td>
                            <td>✎</td>
                        </tr>
                        <tr>
                            <td>005</td>
                            <td>Microsoft</td>
                            <td>Manutenção</td>
                            <td>Funcionario 06</td>
                            <td>22/05/25</td>
                            <td><span class="status">Aberto</span></td>
                            <td>✎</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>