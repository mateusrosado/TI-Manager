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
    <a href="<?= BASE_URL; ?>Adm/home">Home</a>
    <a href="<?= BASE_URL; ?>Adm/empresas">Empresas</a>
    <a href="<?= BASE_URL; ?>Adm/funcionarios">Funcionarios</a>
    <a class="active" href="<?= BASE_URL; ?>Adm/historico">Historico</a>
    <a href="<?= BASE_URL; ?>Adm/logout/" style="margin-top: auto;">Logout</a>
  </aside>  

  <main class="main">
    <section id="historico">
      <div class="header-section">
        <h2><?= htmlspecialchars($viewData['empresa']['company_name'] ?? 'Empresa'); ?></h2>
        <button class="btn" onclick="window.location.href='../AddChamado/'">+ Novo Chamado</button>
      </div>
      <div class="container-table">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Chamado</th>
              <th>Descrição</th>
              <th>Solicitante</th>
              <th>Responsável</th>
              <th>Data Abertura</th>
              <th>Data Fechamento</th>
              <th>Status</th>
              <th>Prioridade</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($viewData['chamados'])): ?>
              <?php foreach ($viewData['chamados'] as $chamado): ?>
                <tr>
                  <td><?= htmlspecialchars($chamado['id']); ?></td>
                  <td><?= htmlspecialchars($chamado['titulo'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($chamado['descricao'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($chamado['solicitante'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($chamado['funcionario_nome'] ?? ''); ?></td>
                  <td><?= !empty($chamado['data_abertura']) ? date('d/m/y', strtotime($chamado['data_abertura'])) : ''; ?></td>
                  <td><?= !empty($chamado['data_fechamento']) ? date('d/m/y', strtotime($chamado['data_fechamento'])) : '-'; ?></td>
                  <td><span class="status"><?= htmlspecialchars($chamado['status'] ?? ''); ?></span></td>
                  <td><?= htmlspecialchars($chamado['prioridade'] ?? ''); ?></td>
                  <td>✎</td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10">Nenhum chamado encontrado para esta empresa.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
  </section>
  </main>
</body>
</html>
