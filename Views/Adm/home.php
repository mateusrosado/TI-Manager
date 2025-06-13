<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Empresas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/tema.css">
</head>

<body class="home">
    <aside>
        <div class="menu-title">🔵MENU</div>
        <div class="user">
            <div class="user-logo">
                𖡌
            </div>
            <div class="user-type">
                <?= htmlspecialchars($viewData['name'] ?? 'Usuário'); ?> (<?= htmlspecialchars($viewData['user_role'] ?? 'Admin'); ?>)
            </div>
        </div>
        <a class="active" href="<?= BASE_URL; ?>Adm/home">Home</a>
        <a href="<?= BASE_URL; ?>Adm/empresas">Empresas</a>
        <a href="<?= BASE_URL; ?>Adm/funcionarios">Funcionarios</a>
        <a href="<?= BASE_URL; ?>Adm/logout/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="home" class="active">
            <div class="header-section">
                <h2>Empresas</h2>
                <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>AddCliente/'">+ Nova Empresa</button>
            </div>
            <div class="card-container">
                <?php if (!empty($viewData['empresas'])): ?>
                    <?php foreach ($viewData['empresas'] as $empresa): ?>
                        <div class="card">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($empresa['company_name']); ?>&background=0D8ABC&color=fff" alt="<?= htmlspecialchars($empresa['company_name']); ?>" width="60">
                            <h3><?= htmlspecialchars($empresa['company_name']); ?></h3>
                            <p><strong><?= $empresa['funcionarios_count'] ?? '0'; ?></strong> Funcionários</p>
                            <p><strong><?= $empresa['chamados_ativos'] ?? '0'; ?></strong> Chamados</p>
                            <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>Adm/historico/?empresa=<?= $empresa['id']; ?>'">Ver mais</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma empresa cadastrada.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    </body>
</html>