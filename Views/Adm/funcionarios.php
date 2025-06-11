<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Funcionários</title>
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
   <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css" />
   <style>
      .modal {
         display: none;
         position: fixed;
         z-index: 1;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         overflow: auto;
         background-color: rgba(0, 0, 0, 0.6);
         justify-content: center;
         align-items: center;
      }

      .modal-content {
         display: flex;
         justify-content: center;
         align-items: center;
         width: 90%;
         max-width: 600px;
      }

      form {
         background-color: #282828;
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
         color: #f0f0f0;
         display: flex;
         flex-direction: column;
         gap: 1.6rem;
         width: 100%;
         border: 1px solid #fff;
         position: relative;
      }

      form h2 {
         text-align: center;
         color: #f0f0f0;
         font-size: 1.8em;
         margin-bottom: 0;
      }

      form .modal-message {
         margin-top: -10px;
         margin-bottom: 5px;
      }

      form > div {
         display: flex;
         flex-direction: column;
         gap: 0.5rem;
         width: 100%;
      }

      form label {
         display: block;
         font-weight: bold;
         color: #cccccc;
      }

      form input[type="text"],
      form input[type="email"],
      form input[type="password"],
      form select {
         width: 100%;
         padding: 12px 10px;
         border: 1px solid #555;
         border-radius: 5px;
         background-color: #3a3a3a;
         color: #f0f0f0;
         font-size: 1em;
         outline: none;
         box-sizing: border-box;
      }

      form input[type="text"]::placeholder,
      form input[type="email"]::placeholder,
      form input[type="password"]::placeholder {
         color: #999;
      }

      .modal-message {
         color: #ffcccc; 
         background-color: #a00;
         padding: 10px;
         border-radius: 5px;
         text-align: center;
         font-size: 1.4rem;
      }
      .modal-message.success {
         color: #ccffcc;
         background-color: #0a0;
      }

      .close-button {
         position: absolute;
         top: 10px;
         right: 15px;
         color: #aaa;
         font-size: 28px;
         font-weight: bold;
         cursor: pointer;
      }

      .close-button:hover,
      .close-button:focus {
         color: white;
         text-decoration: none;
         cursor: pointer;
      }

      form button {
         width: 100%;
         padding: 12px 20px;
         background-color: #4a69bd;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         font-size: 1.1em;
         transition: background-color 0.3s ease;
      }

      form button:hover {
         background-color: #3b5a9b;
      }

      .modal.display-flex {
         display: flex;
      }
   </style>
</head>

<body class="home">
   <aside>
      <div class="menu-title">🔵MENU</div>
      <div class="user">
         <div class="user-logo">𖡌</div>
         <div class="user-type">
            <?= htmlspecialchars($viewData['name'] ?? 'Usuário'); ?> (<?= htmlspecialchars($viewData['user_role'] ?? 'Admin'); ?>)
         </div>
      </div>
      <?php
      $userRole = $viewData['user_role'] ?? '';
      ?>

      <?php if ($userRole == 'admin'): ?>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Adm/home">Home</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Empresas")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Adm/empresas">Empresas</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Funcionarios")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Adm/funcionarios">Funcionários</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Historico")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Adm/historico">Histórico</a>

      <?php elseif ($userRole == 'adm_cliente'): ?>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Home/index">Home</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "MeusChamadosEmpresa")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=ChamadosCliente/todos">Meus Chamados (Empresa)</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "FuncionariosCliente")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Cliente/funcionarios">Funcionários (Cliente)</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "HistoricoEmpresa")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=ChamadosCliente/historico">Histórico da Empresa</a>

      <?php elseif ($userRole == 'funcionario_ti'): ?>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Chamados")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Chamados/index">Chamados</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "MeusChamadosTI")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=FuncionarioTI/meus_chamados">Meus Chamados (TI)</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "HistoricoTI")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=FuncionarioTI/historico">Histórico de Atendimento</a>

      <?php elseif ($userRole == 'funcionario_cliente'): ?>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Chamados")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Chamados/index">Chamados</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "CriarChamado")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Chamado/criar">Criar Chamado</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "MeusChamados")?'active':''; ?>"
            href="<?= BASE_URL; ?>index.php?url=Chamado/meus_chamados">Meus Chamados</a>
      <?php endif; ?>

      <?php
      $logoutController = '';
      if ($userRole == 'admin' || $userRole == 'adm_cliente') {
         $logoutController = 'Adm';
      } elseif ($userRole == 'funcionario_ti' || $userRole == 'funcionario_cliente') {
         $logoutController = 'Funcionario';
      }
      ?>
      <a href="<?= BASE_URL; ?>index.php?url=<?= $logoutController; ?>/logout" style="margin-top: auto;">Logout</a>
   </aside>

   <main class="main">
      <section id="funcionarios">
         <div class="header-section">
            <h2>Funcionarios</h2>
            <button class="btn" onclick="openModal()">+ Novo Funcionario</button>
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
                  <?php if (!empty($viewData['funcionarios'])): ?>
                     <?php foreach ($viewData['funcionarios'] as $funcionario): ?>
                        <tr>
                           <td><?= htmlspecialchars($funcionario['id']); ?></td>
                           <td><?= htmlspecialchars($funcionario['name']); ?></td>
                           <td><?= htmlspecialchars($funcionario['role']); ?></td>
                           <td><?= htmlspecialchars($funcionario['company_name'] ?? 'Não Atribuído'); ?></td>
                           <td><?= htmlspecialchars(date('d/m/Y', strtotime($funcionario['created_at']))); ?></td>
                           <td>
                              <a href="<?= BASE_URL; ?>index.php?url=Funcionario/editar/<?= $funcionario['id']; ?>">✎</a>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr>
                        <td colspan="6">Nenhum funcionário cadastrado.</td>
                     </tr>
                  <?php endif; ?>
               </tbody>
            </table>
         </div>
      </section>
   </main>

   <div id="addFuncionarioModal" class="modal" role="dialog" aria-labelledby="modalTitle" aria-modal="true">
      <div class="modal-content">
         <form method="post" action="<?= BASE_URL; ?>index.php?url=Adm/createFuncionarioTI">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Adicionar Novo Funcionário</h2>
            
            <?php if (isset($viewData['error_message']) && !empty($viewData['error_message'])): ?>
               <div class="modal-message">
                  <?= htmlspecialchars($viewData['error_message']); ?>
               </div>
            <?php endif; ?>
            <?php if (isset($viewData['success_message']) && !empty($viewData['success_message'])): ?>
               <div class="modal-message success">
                  <?= htmlspecialchars($viewData['success_message']); ?>
               </div>
            <?php endif; ?>

            <div>
               <label for="name">Nome</label>
               <input type="text" id="name" name="name" placeholder="Nome do Funcionário" required maxlength="100">
            </div>
            <div>
               <label for="cpf">CPF</label>
               <input type="text" id="cpf" name="cpf" placeholder="CPF do Funcionário" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Formato: 000.000.000-00" maxlength="14">
            </div>
            <div>
               <label for="email">Email</label>
               <input type="email" id="email" name="email" placeholder="Digite um Email" required maxlength="100">
            </div>
            <div>
               <label for="funcao">Funcao</label>
               <input type="text" id="funcao" name="funcao" placeholder="Função do funcionário" required maxlength="100">
            </div>
            <div>
               <label for="password">Senha</label>
               <input type="password" id="password" name="password" placeholder="Digite uma senha" required minlength="<?= CONF_PASSWD_MIN_LEN; ?>" maxlength="<?= CONF_PASSWD_MAX_LEN; ?>">
            </div>
            <div>
               <label for="client_id">Atribuir à Empresa (Opcional)</label>
               <select id="client_id" name="client_id">
                  <option value="">Não Atribuído</option>
                  <?php
                  if (!empty($viewData['empresas'])) {
                     foreach ($viewData['empresas'] as $empresa) {
                        echo '<option value="' . htmlspecialchars($empresa['id']) . '">' . htmlspecialchars($empresa['company_name']) . '</option>';
                     }
                  }
                  ?>
               </select>
            </div>

            <button type="submit">Salvar</button>
         </form>
      </div>
   </div>

   <script>
      var modal = document.getElementById("addFuncionarioModal");

      function openModal() {
         modal.style.display = "flex";
      }

      function closeModal() {
         modal.style.display = "none";
      }

      window.onclick = function(event) {
         if (event.target == modal) {
            modal.style.display = "none";
         }
      }

      // Exibe o modal automaticamente se houver mensagens de erro ou sucesso
      <?php if (isset($viewData['error_message']) || isset($viewData['success_message'])): ?>
         openModal();
      <?php endif; ?>

      // Máscara de CPF usando jQuery Mask Plugin
      if (typeof jQuery !== 'undefined') {
         jQuery(document).ready(function($) {
            $('#cpf').mask('000.000.000-00', {reverse: false});
         });
      }
   </script>
</body>

</html>
