<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Funcionarios</title>
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
      .modal-form {
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
      .modal-form h2 {
         text-align: center;
         color: #f0f0f0;
         margin-bottom: 0;
         font-size: 1.8em;
      }
      .modal-form > div {
         display: flex;
         flex-direction: column;
         gap: 0.5rem;
         width: 100%;
      }
      .modal-form label {
         display: block;
         font-weight: bold;
         color: #cccccc;
      }
      .modal-form input[type="text"],
      .modal-form input[type="email"],
      .modal-form input[type="password"],
      .modal-form select {
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
      .modal-form input[type="text"]::placeholder,
      .modal-form input[type="email"]::placeholder,
      .modal-form input[type="password"]::placeholder {
         color: #999;
      }
      .modal-form .modal-message {
         color: #ffcccc; 
         background-color: #a00;
         padding: 10px;
         border-radius: 5px;
         margin-top: -10px;
         margin-bottom: 5px;
         text-align: center;
         font-size: 1.4rem;
      }
      .modal-form .modal-message.success {
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
      .modal-form button[type="submit"] {
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
      .modal-form button[type="submit"]:hover {
         background-color: #3b5a9b;
      }
      .container-table td a, .container-table td button {
         display: inline-block;
         margin-right: 5px;
         vertical-align: middle;
      }
      .icon-edit {
         display: inline-block;
         width: 18px;
         height: 18px;
         vertical-align: middle;
         background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 3a2.85 2.85 0 0 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'/%3E%3C/svg%3E");
         background-repeat: no-repeat;
         background-position: center;
         background-size: contain;
         color: #4a69bd;
      }
      .container-table td a[title="Editar funcionario"] {
         color: #4a69bd;
         text-decoration: none;
      }
      .container-table td a[title="Editar funcionario"]:hover {
         opacity: 0.8;
      }
      .btn-inativar {
         background-color: #dc3545;
         color: white;
         border: none;
         padding: 5px 10px;
         border-radius: 5px;
         cursor: pointer;
         font-size: 0.9em;
         transition: background-color 0.3s ease;
      }
      .btn-inativar:hover {
         background-color: #c82333;
      }
      .btn-ativar {
         background-color: #28a745;
         color: white;
         border: none;
         padding: 5px 10px;
         border-radius: 5px;
         cursor: pointer;
         font-size: 0.9em;
         transition: background-color 0.3s ease;
      }
      .btn-ativar:hover {
         background-color: #218838;
      }
      .confirm-buttons {
         display: flex;
         justify-content: space-around;
         gap: 15px;
         margin-top: 20px;
      }
      .confirm-buttons button {
         width: 48%;
         padding: 10px 15px;
         border-radius: 5px;
         cursor: pointer;
         font-size: 1em;
      }
      .confirm-buttons .btn-confirm-yes {
         background-color: #28a745;
         color: white;
         border: none;
      }
      .confirm-buttons .btn-confirm-yes:hover {
         background-color: #218838;
      }
      .confirm-buttons .btn-confirm-no {
         background-color: #6c757d;
         color: white;
         border: none;
      }
      .confirm-buttons .btn-confirm-no:hover {
         background-color: #5a6268;
      }
      #confirmActionModal .modal-content {
         max-width: 450px;
         padding: 30px;
         background-color: #282828;
         border-radius: 8px;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
         color: #f0f0f0;
         flex-direction: column;
         gap: 15px;
         position: relative;
         border: 1px solid #fff;
      }
      #confirmActionModal .modal-content h2 {
         margin-bottom: 0;
         font-size: 1.6em;
      }
      #confirmActionModal .modal-content .modal-message {
         margin-top: 0;
         margin-bottom: 0;
         font-size: 1.1em;
         padding: 10px;
         border-radius: 5px;
      }
      #confirmActionModal .close-button {
         top: 10px;
         right: 15px;
      }
      #globalToast {
         position: fixed;
         top: 20px;
         right: 20px;
         padding: 15px 25px;
         border-radius: 8px;
         color: white;
         font-size: 1.1em;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
         z-index: 1001;
         display: none;
         opacity: 0;
         transition: opacity 0.5s ease-in-out;
      }
      #globalToast.success {
         background-color: #28a745;
      }
      #globalToast.error {
         background-color: #dc3545;
      }
   </style>
</head>

<body class="home">
   <aside>
      <div class="menu-title">🔵MENU</div>
      <div class="user">
         <div class="user-logo">𖡌</div>
         <div class="user-type">
            <?= htmlspecialchars($viewData['name'] ?? 'Usuario'); ?> (<?= htmlspecialchars($viewData['user_role'] ?? 'Admin'); ?>)
         </div>
      </div>
      <?php
      $userRole = $viewData['user_role'] ?? '';
      ?>

      <?php if ($userRole == 'admin'): ?>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home")?'active':''; ?>"
            href="<?= BASE_URL; ?>Adm/home">Home</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Empresas")?'active':''; ?>"
            href="<?= BASE_URL; ?>Adm/empresas">Empresas</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Funcionarios")?'active':''; ?>"
            href="<?= BASE_URL; ?>Adm/funcionarios">Funcionarios</a>

      <?php elseif ($userRole == 'adm_cliente'): ?>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home")?'active':''; ?>"
            href="<?= BASE_URL; ?>Home/index">Home</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "MeusChamadosEmpresa")?'active':''; ?>"
            href="<?= BASE_URL; ?>ChamadosCliente/todos">Meus Chamados (Empresa)</a>
         <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "FuncionariosCliente")?'active':''; ?>"
            href="<?= BASE_URL; ?>Cliente/funcionarios">Funcionarios (Cliente)</a>
       <?php endif; ?>

      <?php
      $logoutController = '';
      if ($userRole == 'admin' || $userRole == 'adm_cliente') {
         $logoutController = 'Adm';
      } elseif ($userRole == 'funcionario_ti' || $userRole == 'funcionario_cliente') {
         $logoutController = 'Funcionario';
      }
      ?>
      <a href="<?= BASE_URL; ?><?= $logoutController; ?>/logout" style="margin-top: auto;">Logout</a>
   </aside>

   <main class="main">
      <section id="funcionarios">
         <div class="header-section">
            <h2>Funcionarios</h2>
            <button class="btn" onclick="openAddModal()">+ Novo Funcionario</button>
         </div>
         <div class="container-table">
            <table>
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Nome</th>
                     <th>Funcao</th>
                     <th>Atribuido a</th>
                     <th>Data de Registro</th>
                        <th>Status</th>
                     <th>Ações</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if (!empty($viewData['funcionarios'])): ?>
                     <?php foreach ($viewData['funcionarios'] as $funcionario): ?>
                        <tr>
                           <td><?= htmlspecialchars($funcionario['id']); ?></td>
                           <td><?= htmlspecialchars($funcionario['name']); ?></td>
                           <td><?= htmlspecialchars($funcionario['funcao'] ?? 'N/A'); ?></td>
                           <td><?= htmlspecialchars($funcionario['company_name'] ?? 'Nao Atribuido'); ?></td>
                           <td><?= htmlspecialchars(date('d/m/Y', strtotime($funcionario['created_at']))); ?></td>
                           <td><?= $funcionario['is_active'] ? 'Ativo' : 'Inativo'; ?></td>
                           <td>
                              <a href="#" onclick="openEditModal(<?= $funcionario['id']; ?>)" title="Editar funcionario">
                                 <i class="icon-edit"></i>
                              </a>
                              <?php if ($funcionario['is_active']): ?>
                                 <button class="btn-inativar" onclick="openConfirmActionModal(<?= $funcionario['id']; ?>, '<?= htmlspecialchars($funcionario['name'], ENT_QUOTES, 'UTF-8'); ?>', 'inativar')" title="Inativar funcionario">✕</button>
                              <?php else: ?>
                                 <button class="btn-ativar" onclick="openConfirmActionModal(<?= $funcionario['id']; ?>, '<?= htmlspecialchars($funcionario['name'], ENT_QUOTES, 'UTF-8'); ?>', 'ativar')" title="Ativar funcionario">✔</button>
                              <?php endif; ?>
                           </td>
                        </tr>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <tr>
                        <td colspan="7">Nenhum funcionario cadastrado.</td>
                     </tr>
                  <?php endif; ?>
               </tbody>
            </table>
         </div>
      </section>
   </main>

   <div id="addFuncionarioModal" class="modal" role="dialog" aria-labelledby="addModalTitle" aria-modal="true">
      <div class="modal-content">
         <form method="post" action="<?= BASE_URL; ?>Adm/createFuncionarioTI" class="modal-form">
            <span class="close-button" onclick="closeAddModal()">&times;</span>
            <h2 id="addModalTitle">Adicionar Novo Funcionario</h2>
            
            <?php if (isset($viewData['add_error_message']) && !empty($viewData['add_error_message'])): ?>
               <div class="modal-message">
                  <?= htmlspecialchars($viewData['add_error_message']); ?>
               </div>
            <?php endif; ?>
            <?php if (isset($viewData['add_success_message']) && !empty($viewData['add_success_message'])): ?>
               <div class="modal-message success">
                  <?= htmlspecialchars($viewData['add_success_message']); ?>
               </div>
            <?php endif; ?>

            <div>
               <label for="add_name">Nome</label>
               <input type="text" id="add_name" name="name" placeholder="Nome do Funcionario" required maxlength="100">
            </div>
            <div>
               <label for="add_cpf">CPF</label>
               <input type="text" id="add_cpf" name="cpf" placeholder="CPF do Funcionario" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Formato: 000.000.000-00" maxlength="14">
            </div>
            <div>
               <label for="add_email">Email</label>
               <input type="email" id="add_email" name="email" placeholder="Digite um Email" required maxlength="100">
            </div>
            <div>
               <label for="add_funcao">Funcao</label>
               <input type="text" id="add_funcao" name="funcao" placeholder="Funcao do funcionario" required maxlength="100">
            </div>
            <div>
               <label for="add_password">Senha</label>
               <input type="password" id="add_password" name="password" placeholder="Digite uma senha" required minlength="<?= CONF_PASSWD_MIN_LEN; ?>" maxlength="<?= CONF_PASSWD_MAX_LEN; ?>">
            </div>
            <div>
               <label for="add_client_id">Atribuir a Empresa (Opcional)</label>
               <select id="add_client_id" name="client_id">
                  <option value="">Nao Atribuido</option>
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

   <div id="editFuncionarioModal" class="modal" role="dialog" aria-labelledby="editModalTitle" aria-modal="true">
      <div class="modal-content">
         <form method="post" action="<?= BASE_URL; ?>Adm/updateFuncionarioTI" class="modal-form">
            <span class="close-button" onclick="closeEditModal()">&times;</span>
            <h2 id="editModalTitle">Editar Funcionario</h2>
            
            <?php if (isset($viewData['edit_error_message']) && !empty($viewData['edit_error_message'])): ?>
               <div class="modal-message">
                  <?= htmlspecialchars($viewData['edit_error_message']); ?>
               </div>
            <?php endif; ?>
            <?php if (isset($viewData['edit_success_message']) && !empty($viewData['edit_success_message'])): ?>
               <div class="modal-message success">
                  <?= htmlspecialchars($viewData['edit_success_message']); ?>
               </div>
            <?php endif; ?>

            <input type="hidden" id="edit_id" name="id">
            <div>
               <label for="edit_name">Nome</label>
               <input type="text" id="edit_name" name="name" placeholder="Nome do Funcionario" required maxlength="100">
            </div>
            <div>
               <label for="edit_cpf">CPF</label>
               <input type="text" id="edit_cpf" name="cpf" placeholder="CPF do Funcionario" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Formato: 000.000.000-00" maxlength="14">
            </div>
            <div>
               <label for="edit_email">Email</label>
               <input type="email" id="edit_email" name="email" placeholder="Digite um Email" required maxlength="100">
            </div>
            <div>
               <label for="edit_funcao">Funcao</label>
               <input type="text" id="edit_funcao" name="funcao" placeholder="Funcao do funcionario" required maxlength="100">
            </div>
            <div>
               <label for="edit_password">Nova Senha (deixe em branco para nao alterar)</label>
               <input type="password" id="edit_password" name="password" placeholder="Nova Senha" minlength="<?= CONF_PASSWD_MIN_LEN; ?>" maxlength="<?= CONF_PASSWD_MAX_LEN; ?>">
            </div>
            <div>
               <label for="edit_client_id">Atribuir a Empresa (Opcional)</label>
               <select id="edit_client_id" name="client_id">
                  <option value="">Nao Atribuido</option>
                  <?php
                  if (!empty($viewData['empresas'])) {
                     foreach ($viewData['empresas'] as $empresa) {
                        echo '<option value="' . htmlspecialchars($empresa['id']) . '">' . htmlspecialchars($empresa['company_name']) . '</option>';
                     }
                  }
                  ?>
               </select>
            </div>

            <button type="submit">Salvar Alteracoes</button>
         </form>
      </div>
   </div>

   <div id="confirmActionModal" class="modal" role="dialog" aria-labelledby="confirmModalTitle" aria-modal="true">
      <div class="modal-content">
         <span class="close-button" onclick="closeConfirmModal()">&times;</span>
         <h2 id="confirmModalTitle"></h2>
         <div class="modal-message" id="confirmModalMessage">
         </div>
         <div class="confirm-buttons">
            <button class="btn-confirm-yes" id="confirmActionBtn">Sim</button>
            <button class="btn-confirm-no" onclick="closeConfirmModal()">Nao, Cancelar</button>
         </div>
      </div>
   </div>

   <div id="globalToast" class="toast-message"></div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
   <script>
      const addModal = document.getElementById("addFuncionarioModal");
      const editModal = document.getElementById("editFuncionarioModal");
      const confirmModal = document.getElementById("confirmActionModal");
      const confirmModalTitle = document.getElementById("confirmModalTitle");
      const confirmModalMessage = document.getElementById("confirmModalMessage");
      const confirmActionBtn = document.getElementById("confirmActionBtn");

      let currentActionFuncionarioId = null;
      let currentActionType = '';

      function openAddModal() {
         addModal.style.display = "flex";
         addModal.querySelectorAll('.modal-message').forEach(msg => msg.remove());
         addModal.querySelector('form').reset(); 
      } 
      function closeAddModal() {
         addModal.style.display = "none";
         addModal.querySelector('form').reset();
      } 
      function openEditModal(id) {
         const isAutoOpening = window.autoOpeningEditModal || false; 
         window.autoOpeningEditModal = false;
         if (!isAutoOpening) {
            editModal.querySelectorAll('.modal-message').forEach(msg => msg.remove());
            editModal.querySelector('form').reset(); 
         }
         if (typeof jQuery !== 'undefined') {
            $.ajax({
               url: `<?= BASE_URL; ?>Adm/getFuncionarioTIJson/${id}`,
               method: 'GET',
               dataType: 'json',
               success: function(response) {
                  if (response && response.id) {
                     $('#edit_id').val(response.id);
                     $('#edit_name').val(response.name);
                     $('#edit_cpf').val(response.cpf);
                     $('#edit_cpf').mask('000.000.000-00', {reverse: false}).val(response.cpf).trigger('input'); 
                     $('#edit_email').val(response.email);
                     $('#edit_funcao').val(response.funcao || ''); 
                     $('#edit_client_id').val(response.client_id || ''); 
                     editModal.style.display = "flex";
                  } else {
                     alert('Funcionario nao encontrado ou erro ao carregar dados.');
                  }
               },
               error: function(xhr, status, error) {
                  console.error('Erro ao carregar dados do funcionario:', error);
                  alert('Erro ao carregar dados do funcionario.');
               }
            });
         } else {
            alert('Funcionalidade de edicao requer jQuery ou uma implementacao AJAX personalizada.');
            editModal.style.display = "flex";
         }
      }
      function closeEditModal() {
         editModal.style.display = "none";
         editModal.querySelector('form').reset();
      }
      function openConfirmActionModal(id, name, actionType) {
         currentActionFuncionarioId = id;
         currentActionType = actionType;
         if (actionType === 'inativar') {
            confirmModalTitle.textContent = "Confirmar Inativação";
            confirmModalMessage.innerHTML = `Tem certeza que deseja inativar o funcionario <strong>${name}</strong>?<br>Ele nao podera ter chamados ativos nem estar atribuido a uma empresa.`;
            confirmActionBtn.textContent = "Sim, Inativar";
            confirmActionBtn.className = "btn-confirm-yes btn-inativar";
         } else if (actionType === 'ativar') {
            confirmModalTitle.textContent = "Confirmar Ativacao";
            confirmModalMessage.innerHTML = `Tem certeza que deseja ativar o funcionario <strong>${name}</strong>?<br>Ele podera fazer login e ter chamados novamente.`;
            confirmActionBtn.textContent = "Sim, Ativar";
            confirmActionBtn.className = "btn-confirm-yes btn-ativar";
         }
         confirmModal.style.display = "flex";
         confirmModalMessage.classList.remove('success');
         confirmModalMessage.style.color = '#f0f0f0';
         confirmModalMessage.style.backgroundColor = 'transparent';
         $('.confirm-buttons').show();
      }
      function closeConfirmModal() {
         confirmModal.style.display = "none";
         currentActionFuncionarioId = null;
         currentActionType = '';
         $('#confirmModalMessage').html('Tem certeza que deseja inativar o funcionario <strong id="funcionarioNomeConfirm"></strong>? Ele nao podera ter chamados ativos nem estar atribuido a uma empresa.');
         $('#funcionarioNomeConfirm').text('');
         $('.confirm-buttons').show();
      }
      confirmActionBtn.addEventListener('click', () => {
         if (currentActionFuncionarioId && currentActionType) {
            let actionUrl = '';
            if (currentActionType === 'inativar') {
               actionUrl = `<?= BASE_URL; ?>Adm/inativarFuncionario/${currentActionFuncionarioId}`;
            } else if (currentActionType === 'ativar') {
               actionUrl = `<?= BASE_URL; ?>Adm/ativarFuncionario/${currentActionFuncionarioId}`;
            }
            if (actionUrl && typeof jQuery !== 'undefined') {
               $.ajax({
                  url: actionUrl,
                  method: 'POST',
                  dataType: 'json',
                  success: function(response) {
                     confirmModalMessage.textContent = response.message;
                     if (response.success) {
                        confirmModalMessage.classList.add('success');
                        confirmModalMessage.style.color = '#ccffcc';
                        confirmModalMessage.style.backgroundColor = '#0a0';
                        setTimeout(() => { window.location.reload(); }, 1500);
                     } else {
                        confirmModalMessage.classList.remove('success');
                        confirmModalMessage.style.color = '#ffcccc';
                        confirmModalMessage.style.backgroundColor = '#a00';
                     }
                     $('.confirm-buttons').hide();
                  },
                  error: function(xhr, status, error) {
                     console.error('Erro na requisicao da acao:', error);
                     confirmModalMessage.textContent = 'Erro ao executar a acao. Verifique o console para mais detalhes.';
                     confirmModalMessage.classList.remove('success');
                     confirmModalMessage.style.color = '#ffcccc';
                     confirmModalMessage.style.backgroundColor = '#a00';
                     $('.confirm-buttons').hide();
                  }
               });
            } else {
               alert('Funcionalidade de acao requer jQuery ou implementacao AJAX personalizada.');
            }
         }
      });
      window.onclick = function(event) {
         if (event.target === addModal) {
            closeAddModal();
         }
         if (event.target === editModal) {
            closeEditModal();
         }
         if (event.target === confirmModal) {
            closeConfirmModal();
         }
      };
      <?php if (isset($viewData['error_message'])): ?>
         window.autoOpeningAddModal = true;
         openAddModal();
      <?php endif; ?>
      <?php if (isset($viewData['edit_error_message']) || isset($viewData['edit_success_message'])): ?>
         window.autoOpeningEditModal = true; 
         const editingFuncionarioId = <?= isset($viewData['editing_funcionario_id']) ? $viewData['editing_funcionario_id'] : 'null'; ?>;
         if (editingFuncionarioId) {
            openEditModal(editingFuncionarioId);
         } else {
            editModal.style.display = "flex";
         }
      <?php endif; ?>
      <?php if (isset($viewData['inativar_error_message']) || isset($viewData['inativar_success_message']) || isset($viewData['ativar_error_message']) || isset($viewData['ativar_success_message'])): ?>
         confirmModal.style.display = "flex";
         const inativateMessageDiv = $('#confirmModalMessage');
         inativateMessageDiv.css({
            'color': 'red',
            'background-color': '#a00'
         });
         <?php if (isset($viewData['inativar_error_message'])): ?>
            confirmModalTitle.textContent = "Erro na Inativação";
            inativateMessageDiv.text('<?= htmlspecialchars($viewData["inativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>');
         <?php elseif (isset($viewData['inativar_success_message'])): ?>
            confirmModalTitle.textContent = "Inativação Realizada";
            inativateMessageDiv.html('<span style="color:green;">' + '<?= htmlspecialchars($viewData["inativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>' + '</span>');
            inativateMessageDiv.css({
               'color': 'green',
               'background-color': '#0a0'
            });
         <?php elseif (isset($viewData['ativar_error_message'])): ?>
            confirmModalTitle.textContent = "Erro na Ativação";
            inativateMessageDiv.text('<?= htmlspecialchars($viewData["ativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>');
         <?php elseif (isset($viewData['ativar_success_message'])): ?>
            confirmModalTitle.textContent = "Ativação Realizada";
            inativateMessageDiv.html('<span style="color:green;">' + '<?= htmlspecialchars($viewData["ativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>' + '</span>');
            inativateMessageDiv.css({
               'color': 'green',
               'background-color': '#0a0'
            });
         <?php endif; ?>
         $('.confirm-buttons').hide();
         <?php if (isset($viewData['inativar_success_message']) || isset($viewData['ativar_success_message'])): ?>
            setTimeout(() => { closeConfirmModal(); }, 3000);
         <?php endif; ?>
      <?php endif; ?>
      function displayToast(message, type) {
         let toast = document.getElementById('globalToast');
         if (!toast) {
            toast = document.createElement('div');
            toast.id = 'globalToast';
            toast.className = 'toast-message';
            document.body.appendChild(toast);
         }
         toast.textContent = message;
         toast.className = 'toast-message ' + type;
         toast.style.display = 'block';
         toast.style.opacity = '1';
         setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
               toast.style.display = 'none';
               toast.textContent = '';
            }, 500);
         }, 5000);
      }
      if (typeof jQuery !== 'undefined') {
         jQuery(document).ready(function($) {
            $('#add_cpf').mask('000.000.000-00', {reverse: false});
            $('#edit_cpf').mask('000.000.000-00', {reverse: false});
         });
      }
      <?php if (isset($viewData['add_success_message'])): ?>
         closeAddModal();
         displayToast('<?= htmlspecialchars($viewData["add_success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
      <?php elseif (isset($viewData['add_error_message'])): ?>
         displayToast('<?= htmlspecialchars($viewData["add_error_message"], ENT_QUOTES, 'UTF-8'); ?>', 'error');
      <?php endif; ?>

      <?php if (isset($viewData['edit_success_message'])): ?>
         closeEditModal();
         displayToast('<?= htmlspecialchars($viewData["edit_success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
      <?php elseif (isset($viewData['edit_error_message'])): ?>
         displayToast('<?= htmlspecialchars($viewData["edit_error_message"], ENT_QUOTES, 'UTF-8'); ?>', 'error');
      <?php endif; ?>

      <?php if (isset($viewData['inativar_success_message'])): ?>
         displayToast('<?= htmlspecialchars($viewData["inativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
      <?php elseif (isset($viewData['inativar_error_message'])): ?>
         displayToast('<?= htmlspecialchars($viewData["inativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>', 'error');
      <?php endif; ?>

      <?php if (isset($viewData['ativar_success_message'])): ?>
         displayToast('<?= htmlspecialchars($viewData["ativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
      <?php elseif (isset($viewData['ativar_error_message'])): ?>
         displayToast('<?= htmlspecialchars($viewData["ativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>', 'error');
      <?php endif; ?>
   </script>
</body>

</html>
