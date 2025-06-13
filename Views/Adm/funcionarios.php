<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Funcionarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css" />
    <style>
        /* Estilos Comuns do Modal */
        .modal {
            display: none; /* Oculto por padrao */
            position: fixed; /* Permanece na tela */
            z-index: 1; /* Fica no topo */
            left: 0;
            top: 0;
            width: 100%; /* Largura total */
            height: 100%; /* Altura total */
            overflow: auto; /* Permite rolagem se necessario */
            background-color: rgba(0, 0, 0, 0.6); /* Fundo preto com opacidade */
            justify-content: center; /* Centraliza horizontalmente */
            align-items: center; /* Centraliza verticalmente */
        }

        /* Conteiner do Conteudo do Modal (para centralizacao, estilos visuais movidos para .modal-form) */
        /* Esta classe atua como um wrapper para o formulario real, centralizado na sobreposicao do modal. */
        .modal-content {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 90%;
            max-width: 600px; /* Largura maxima para a caixa do modal */
        }

        /* Estilos para os Formularios do Modal (tanto de adicao quanto de edicao) */
        /* Esta classe mantem todo o estilo visual da area de conteudo principal do modal. */
        .modal-form {
            background-color: #282828;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: #f0f0f0;
            display: flex;
            flex-direction: column;
            gap: 1.6rem; /* Espacamento entre os elementos do formulario */
            width: 100%; /* Ocupa a largura total do seu pai (.modal-content) */
            border: 1px solid #fff; /* Borda branca */
            position: relative; /* Essencial para posicionar o botao de fechar relativo a este formulario */
        }

        /* Titulo dentro do formulario do modal */
        .modal-form h2 {
            text-align: center;
            color: #f0f0f0;
            margin-bottom: 0; /* Espaco gerenciado pela propriedade 'gap' do formulario */
            font-size: 1.8em;
        }

        /* Estilo para grupo de label/input dentro do formulario */
        .modal-form > div {
            display: flex;
            flex-direction: column; /* Label acima do input */
            gap: 0.5rem; /* Pequeno espacamento entre label e input */
            width: 100%;
        }

        /* Labels dentro do formulario */
        .modal-form label {
            display: block;
            font-weight: bold;
            color: #cccccc;
        }

        /* Inputs e selects dentro do formulario */
        .modal-form input[type="text"],
        .modal-form input[type="email"],
        .modal-form input[type="password"],
        .modal-form select {
            width: 100%; /* Largura total da sua div pai */
            padding: 12px 10px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #3a3a3a;
            color: #f0f0f0;
            font-size: 1em;
            outline: none;
            box-sizing: border-box; /* Inclui padding e borda na largura/altura total do elemento */
        }

        /* Estilo do placeholder para inputs */
        .modal-form input[type="text"]::placeholder,
        .modal-form input[type="email"]::placeholder,
        .modal-form input[type="password"]::placeholder {
            color: #999;
        }

        /* Estilo para mensagens de erro/sucesso dentro do formulario */
        .modal-form .modal-message {
            color: #ffcccc; 
            background-color: #a00;
            padding: 10px;
            border-radius: 5px;
            margin-top: -10px; /* Ajusta o espacamento abaixo do titulo */
            margin-bottom: 5px; /* Ajusta o espacamento acima do primeiro campo */
            text-align: center;
            font-size: 1.4rem;
        }
        .modal-form .modal-message.success {
            color: #ccffcc;
            background-color: #0a0;
        }

        /* Posicionamento e estilo do botao de fechar (X) */
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

        /* Botao de envio dentro do formulario */
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

        /* Estilo para os botoes de acao na tabela (Editar, Inativar/Ativar) */
        .container-table td a, .container-table td button {
            display: inline-block; /* Permite alinhar lado a lado */
            margin-right: 5px; /* Espacamento entre eles */
            vertical-align: middle; /* Alinha no meio da celula */
        }

        /* Estilo para o icone de edicao (SVG) */
        .icon-edit {
            display: inline-block;
            width: 18px; /* Tamanho do icone */
            height: 18px; /* Tamanho do icone */
            vertical-align: middle;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 3a2.85 2.85 0 0 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            color: #4a69bd; /* Cor padrao para o link, o SVG usara 'currentColor' */
        }
        /* Ajuste a cor do link de editar para combinar com o icone, se desejar */
        .container-table td a[title="Editar funcionario"] {
            color: #4a69bd; /* Cor do link de edicao */
            text-decoration: none; /* Remove sublinhado padrao */
        }
        .container-table td a[title="Editar funcionario"]:hover {
            opacity: 0.8; /* Pequeno efeito de hover */
        }


        /* Estilo para o botao de inativar */
        .btn-inativar {
            background-color: #dc3545; /* Vermelho Bootstrap */
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

        /* Estilo para o botao de ativar (para funcionarios inativos) */
        .btn-ativar {
            background-color: #28a745; /* Verde Bootstrap */
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

        /* Estilo para os botoes de confirmacao dentro do modal de confirmacao */
        .confirm-buttons {
            display: flex;
            justify-content: space-around;
            gap: 15px;
            margin-top: 20px;
        }
        .confirm-buttons button {
            width: 48%; /* Para ficarem lado a lado */
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .confirm-buttons .btn-confirm-yes {
            background-color: #28a745; /* Verde para sim */
            color: white;
            border: none;
        }
        .confirm-buttons .btn-confirm-yes:hover {
            background-color: #218838;
        }
        .confirm-buttons .btn-confirm-no {
            background-color: #6c757d; /* Cinza para nao */
            color: white;
            border: none;
        }
        .confirm-buttons .btn-confirm-no:hover {
            background-color: #5a6268;
        }

        /* Estilos especificos para o modal de confirmacao (`#confirmActionModal`) */
        #confirmActionModal .modal-content {
            max-width: 450px; /* Ligeiramente maior para a mensagem */
            padding: 30px; /* Mantem o padding da caixa */
            background-color: #282828; /* Fundo igual ao form */
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: #f0f0f0;
            flex-direction: column; /* Organiza conteudo em coluna */
            gap: 15px; /* Espacamento entre os elementos */
            position: relative; /* Para o botao fechar */
            border: 1px solid #fff; /* Borda branca */
        }
        
        #confirmActionModal .modal-content h2 {
            margin-bottom: 0; /* Ajuste para o gap */
            font-size: 1.6em;
        }
        #confirmActionModal .modal-content .modal-message {
            margin-top: 0; /* Reinicia margin-top */
            margin-bottom: 0; /* Reinicia margin-bottom */
            font-size: 1.1em;
            padding: 10px;
            border-radius: 5px;
        }
        /* Estilos especificos para o close-button dentro do confirm modal */
        #confirmActionModal .close-button {
            top: 10px;
            right: 15px;
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
               href="<?= BASE_URL; ?>index.php?url=Home/index">Home</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Empresas")?'active':''; ?>"
               href="<?= BASE_URL; ?>index.php?url=Adm/empresas">Empresas</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Funcionarios")?'active':''; ?>"
               href="<?= BASE_URL; ?>index.php?url=Adm/funcionarios">Funcionarios</a>

        <?php elseif ($userRole == 'adm_cliente'): ?>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home")?'active':''; ?>"
               href="<?= BASE_URL; ?>index.php?url=Home/index">Home</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "MeusChamadosEmpresa")?'active':''; ?>"
               href="<?= BASE_URL; ?>index.php?url=ChamadosCliente/todos">Meus Chamados (Empresa)</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "FuncionariosCliente")?'active':''; ?>"
               href="<?= BASE_URL; ?>index.php?url=Cliente/funcionarios">Funcionarios (Cliente)</a>
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

    <!-- Modal de Cadastro de Funcionario -->
    <div id="addFuncionarioModal" class="modal" role="dialog" aria-labelledby="addModalTitle" aria-modal="true">
        <div class="modal-content">
            <form method="post" action="<?= BASE_URL; ?>index.php?url=Adm/createFuncionarioTI" class="modal-form">
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
            <form method="post" action="<?= BASE_URL; ?>index.php?url=Adm/updateFuncionarioTI" class="modal-form">
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

    <!-- Adicione o jQuery ANTES do seu script principal para garantir que ele esteja disponivel -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Adicione o jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        // Get modal elements for both add and edit functionalities
        const addModal = document.getElementById("addFuncionarioModal");
        const editModal = document.getElementById("editFuncionarioModal");
        const confirmModal = document.getElementById("confirmActionModal"); // Unified confirmation modal
        const confirmModalTitle = document.getElementById("confirmModalTitle");
        const confirmModalMessage = document.getElementById("confirmModalMessage");
        const confirmActionBtn = document.getElementById("confirmActionBtn");

        let currentActionFuncionarioId = null; // Stores the ID for the confirmation action
        let currentActionType = ''; // Stores the type of action ('inativar' or 'ativar')

        /**
         * Opens the "Add Employee" modal.
         * Clears any previous messages and resets the form.
         */
        function openAddModal() {
            addModal.style.display = "flex";
            // Clear any old messages displayed within the modal
            addModal.querySelectorAll('.modal-message').forEach(msg => msg.remove());
            // Reset all form fields to their default empty state
            addModal.querySelector('form').reset(); 
        } 
        
        /**
         * Closes the "Add Employee" modal.
         * Resets the form after closing.
         */
        function closeAddModal() {
            addModal.style.display = "none";
            addModal.querySelector('form').reset(); // Reset form fields
        } 

        /**
         * Opens the "Edit Employee" modal and populates its fields via AJAX.
         * @param {number} id - The ID of the employee to be edited.
         */
        function openEditModal(id) {
            // Check if this function is being called manually (e.g., via a click) 
            // or automatically due to a PHP-driven message display.
            const isAutoOpening = window.autoOpeningEditModal || false; 
            window.autoOpeningEditModal = false; // Reset the flag immediately

            // Only clear messages and reset form if it's a manual open.
            // If it's auto-opening due to a server-side message, the messages are already rendered.
            if (!isAutoOpening) {
                editModal.querySelectorAll('.modal-message').forEach(msg => msg.remove());
                editModal.querySelector('form').reset(); 
            }

            // Check if jQuery is available for AJAX requests
            if (typeof jQuery !== 'undefined') {
                $.ajax({
                    url: `<?= BASE_URL; ?>index.php?url=Adm/getFuncionarioTIJson/${id}`,
                    method: 'GET',
                    dataType: 'json', // Expecting a JSON response from the server
                    success: function(response) {
                        if (response && response.id) {
                            // Preenche o formulario do modal de edicao
                            $('#edit_id').val(response.id);
                            $('#edit_name').val(response.name);
                            $('#edit_cpf').val(response.cpf); // Define o valor numerico
                                // Forca a mascara a re-aplicar e formatar o CPF
                                $('#edit_cpf').mask('000.000.000-00', {reverse: false}).val(response.cpf).trigger('input'); 
                            $('#edit_email').val(response.email);
                            $('#edit_funcao').val(response.funcao || ''); 
                            $('#edit_client_id').val(response.client_id || ''); 
                            
                            editModal.style.display = "flex"; // Display the modal after populating
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
                // Fallback message if jQuery is not loaded (AJAX functionality won't work)
                alert('Funcionalidade de edicao requer jQuery ou uma implementacao AJAX personalizada.');
                editModal.style.display = "flex"; // Still open the modal, but fields will be empty
            }
        }
        
        /**
         * Closes the "Edit Employee" modal.
         * Resets the form after closing.
         */
        function closeEditModal() {
            editModal.style.display = "none";
            editModal.querySelector('form').reset(); // Reset form fields
        }

        /**
         * Opens the unified confirmation modal for activate/deactivate actions.
         * @param {number} id - The ID of the employee.
         * @param {string} name - The name of the employee.
         * @param {string} actionType - 'inativar' or 'ativar'.
         */
        function openConfirmActionModal(id, name, actionType) {
            currentActionFuncionarioId = id;
            currentActionType = actionType;

            // Set title and message based on actionType
            if (actionType === 'inativar') {
                confirmModalTitle.textContent = "Confirmar Inativacao";
                confirmModalMessage.innerHTML = `Tem certeza que deseja inativar o funcionario <strong>${name}</strong>?<br>Ele nao podera ter chamados ativos nem estar atribuido a uma empresa.`;
                confirmActionBtn.textContent = "Sim, Inativar";
                confirmActionBtn.className = "btn-confirm-yes btn-inativar"; // Use both classes for specific styling if needed
            } else if (actionType === 'ativar') {
                confirmModalTitle.textContent = "Confirmar Ativacao";
                confirmModalMessage.innerHTML = `Tem certeza que deseja ativar o funcionario <strong>${name}</strong>?<br>Ele podera fazer login e ter chamados novamente.`;
                confirmActionBtn.textContent = "Sim, Ativar";
                confirmActionBtn.className = "btn-confirm-yes btn-ativar"; // Use both classes
            }

            confirmModal.style.display = "flex";
            // Clear any old messages and show buttons
            confirmModalMessage.classList.remove('success'); // Ensure success class is removed
            confirmModalMessage.style.color = '#f0f0f0'; // Default text color
            confirmModalMessage.style.backgroundColor = 'transparent'; // Default background
            $('.confirm-buttons').show();
        }

        /**
         * Closes the confirmation modal.
         */
        function closeConfirmModal() {
            confirmModal.style.display = "none";
            currentActionFuncionarioId = null;
            currentActionType = '';
            // Restaura a mensagem original e garante que os botoes voltem
            $('#confirmModalMessage').html('Tem certeza que deseja inativar o funcionario <strong id="funcionarioNomeConfirm"></strong>? Ele nao podera ter chamados ativos nem estar atribuido a uma empresa.');
            $('#funcionarioNomeConfirm').text(''); // Limpa o nome exibido
            $('.confirm-buttons').show(); // Garante que os botoes voltem se estavam escondidos por mensagem de erro
        }

        // Event listener for the dynamic action button in the confirmation modal
        confirmActionBtn.addEventListener('click', () => {
            if (currentActionFuncionarioId && currentActionType) {
                let actionUrl = '';
                if (currentActionType === 'inativar') {
                    actionUrl = `<?= BASE_URL; ?>index.php?url=Adm/inativarFuncionario/${currentActionFuncionarioId}`;
                } else if (currentActionType === 'ativar') {
                    actionUrl = `<?= BASE_URL; ?>index.php?url=Adm/ativarFuncionario/${currentActionFuncionarioId}`;
                }

                if (actionUrl && typeof jQuery !== 'undefined') {
                    $.ajax({
                        url: actionUrl,
                        method: 'POST', // Use POST for data modification actions
                        dataType: 'json',
                        success: function(response) {
                            // Display the response message in the confirmation modal
                            confirmModalMessage.textContent = response.message;
                            if (response.success) {
                                confirmModalMessage.classList.add('success');
                                confirmModalMessage.style.color = '#ccffcc';
                                confirmModalMessage.style.backgroundColor = '#0a0';
                                // Reload page after a short delay for success message
                                setTimeout(() => { window.location.reload(); }, 1500);
                            } else {
                                confirmModalMessage.classList.remove('success');
                                confirmModalMessage.style.color = '#ffcccc';
                                confirmModalMessage.style.backgroundColor = '#a00';
                            }
                            // Esconde os botoes de acao apos a resposta (mostra a mensagem final)
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
            // Do not close the modal here immediately; it will be closed by reload or user.
        });

        /**
         * Global click handler to close modals when clicking outside of them.
         */
        window.onclick = function(event) {
            if (event.target === addModal) {
                closeAddModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === confirmModal) { // Added for the unified confirmation modal
                closeConfirmModal();
            }
        };

        // PHP-driven logic to automatically open modals on page load if messages are present
        // Isso e util para exibir erros de validacao ou mensagens de sucesso apos a submissao de formularios.

        // Auto-open Add Modal
        <?php if (isset($viewData['error_message']) || isset($viewData['success_message'])): ?>
            // Set flag if modal is auto-opening
            window.autoOpeningAddModal = true;
            openAddModal();
        <?php endif; ?>

        // Auto-open Edit Modal
        <?php if (isset($viewData['edit_error_message']) || isset($viewData['edit_success_message'])): ?>
            // Set a flag to indicate that the modal is being opened automatically by PHP.
            // This prevents the `openEditModal` function from clearing messages that PHP just rendered.
            window.autoOpeningEditModal = true; 
            const editingFuncionarioId = <?= isset($viewData['editing_funcionario_id']) ? $viewData['editing_funcionario_id'] : 'null'; ?>;
            if (editingFuncionarioId) {
                openEditModal(editingFuncionarioId); // Re-open the edit modal and load data
            } else {
                editModal.style.display = "flex"; // Fallback: just open the empty modal if ID is missing
            }
        <?php endif; ?>

        // Auto-open Confirmation Modal if there's a message from an activate/deactivate action
        <?php if (isset($viewData['inativar_error_message']) || isset($viewData['inativar_success_message']) || isset($viewData['ativar_error_message']) || isset($viewData['ativar_success_message'])): ?>
            confirmModal.style.display = "flex"; // Apenas exibe o modal
            const inativateMessageDiv = $('#confirmModalMessage');
            // Define a cor e o fundo da mensagem
            inativateMessageDiv.css({
                'color': 'red',
                'background-color': '#a00'
            });

            <?php if (isset($viewData['inativar_error_message'])): ?>
                confirmModalTitle.textContent = "Erro na Inativacao"; // Define o titulo
                inativateMessageDiv.text('<?= htmlspecialchars($viewData["inativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>');
            <?php elseif (isset($viewData['inativar_success_message'])): ?>
                confirmModalTitle.textContent = "Inativacao Realizada"; // Define o titulo
                inativateMessageDiv.html('<span style="color:green;">' + '<?= htmlspecialchars($viewData["inativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>' + '</span>');
                inativateMessageDiv.css({
                    'color': 'green',
                    'background-color': '#0a0'
                });
            <?php elseif (isset($viewData['ativar_error_message'])): ?>
                confirmModalTitle.textContent = "Erro na Ativacao"; // Define o titulo
                inativateMessageDiv.text('<?= htmlspecialchars($viewData["ativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>');
            <?php elseif (isset($viewData['ativar_success_message'])): ?>
                confirmModalTitle.textContent = "Ativacao Realizada"; // Define o titulo
                inativateMessageDiv.html('<span style="color:green;">' + '<?= htmlspecialchars($viewData["ativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>' + '</span>');
                inativateMessageDiv.css({
                    'color': 'green',
                    'background-color': '#0a0'
                });
            <?php endif; ?>
            $('.confirm-buttons').hide(); // Esconde os botoes de acao apos a mensagem
            // Opcional: Fechar o modal de confirmacao automaticamente apos alguns segundos se for sucesso
            <?php if (isset($viewData['inativar_success_message']) || isset($viewData['ativar_success_message'])): ?>
                setTimeout(() => { closeConfirmModal(); }, 3000);
            <?php endif; ?>
        <?php endif; ?>


        // Apply CPF masking using jQuery Mask Plugin
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                $('#add_cpf').mask('000.000.000-00', {reverse: false}); // Mask for add modal CPF input
                $('#edit_cpf').mask('000.000.000-00', {reverse: false}); // Mask for edit modal CPF input
            });
        }
    </script>
</body>

</html>