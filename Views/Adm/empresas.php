<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Empresas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= BASE_URL; ?>Assets/css/tema.css" />
    <style>
        .home form {
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
            width: 100%;
        }
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
            background-color: #282828;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            color: #f0f0f0;
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
        }
        .modal-content h2 {
            text-align: center;
            color: #f0f0f0;
            margin-bottom: 25px;
            font-size: 1.8em;
        }
        .modal-content label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #cccccc;
        }
        .modal-content input[type="text"],
        .modal-content input[type="email"],
        .modal-content input[type="password"],
        .modal-content select,
        .modal-content textarea {
            width: calc(100% - 20px);
            padding: 12px 10px;
            margin-bottom: 20px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #3a3a3a;
            color: #f0f0f0;
            font-size: 1em;
            outline: none;
        }
        .modal-content input[type="text"]::placeholder,
        .modal-content input[type="email"]::placeholder,
        .modal-content input[type="password"]::placeholder,
        .modal-content textarea::placeholder {
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
            right: 20px;
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
        .modal-content button {
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
        .modal-content button:hover {
            background-color: #3b5a9b;
        }
        .container-table {
            width: 100%;
            overflow-x: auto;
            max-width: 120rem !important;
        }
        .container-table table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        .container-table th, .container-table td {
            padding: 12px 10px;
            text-align: left;
            white-space: nowrap;
        }
        .container-table th:nth-child(2), .container-table td:nth-child(2) { min-width: 220px; }
        .container-table th:nth-child(5), .container-table td:nth-child(5) { min-width: 220px; }
        .container-table th, .container-table td { max-width: 400px; word-break: break-word; }

        /* RESPONSIVIDADE MELHORADA */
        @media (max-width: 900px) {
            .container-table {
                overflow-x: visible;
            }
            .container-table table,
            .container-table thead,
            .container-table tbody,
            .container-table th,
            .container-table td,
            .container-table tr {
                display: block;
                width: 100%;
            }
            .container-table thead {
                display: none;
            }
            .container-table tr {
                margin-bottom: 1.5rem;
                border-bottom: 1px solid #444;
                background: #232323;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                padding: 10px 0;
            }
            .container-table td {
                position: relative;
                padding-left: 50%;
                min-width: unset;
                max-width: unset;
                white-space: normal;
                border: none;
                box-sizing: border-box;
                background: none;
            }
            .container-table td:before {
                position: absolute;
                left: 10px;
                top: 12px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #aaa;
                content: attr(data-label);
                text-align: left;
            }
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
        .modal-form {
            background-color: #282828;
            padding: 0;
            border-radius: 8px;
            color: #f0f0f0;
            display: flex;
            flex-direction: column;
            gap: 1.6rem;
            width: 100%;
            position: relative;
        }
        .modal-form h2 {
            text-align: center;
            color: #f0f0f0;
            margin-bottom: 0;
            font-size: 1.8em;
        }
        .modal-form>div {
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
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home") ? 'active' : ''; ?>"
                href="<?= BASE_URL; ?>index.php?url=Home/index">Home</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Empresas") ? 'active' : ''; ?>"
                href="<?= BASE_URL; ?>index.php?url=Adm/empresas">Empresas</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Funcionarios") ? 'active' : ''; ?>"
                href="<?= BASE_URL; ?>index.php?url=Adm/funcionarios">Funcionarios</a>
        <?php elseif ($userRole == 'adm_cliente'): ?>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Home") ? 'active' : ''; ?>"
                href="<?= BASE_URL; ?>index.php?url=Home/index">Home</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "MeusChamadosEmpresa") ? 'active' : ''; ?>"
                href="<?= BASE_URL; ?>index.php?url=ChamadosCliente/todos">Meus Chamados (Empresa)</a>
            <a class="<?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "FuncionariosCliente") ? 'active' : ''; ?>"
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
                <h2>Empresas</h2>
                <button class="btn" onclick="openAddModal()">+ Nova Empresa</button>
            </div>
            <div class="container-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Empresa</th>
                            <th>CNPJ</th>
                            <th>Contato</th>
                            <th>Endereço</th>
                            <th>Data de abertura</th>
                            <th>Funcionários</th> <!-- NOVA COLUNA -->
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($viewData['empresas'])): ?>
                            <?php foreach ($viewData['empresas'] as $empresa): ?>
                                <tr>
                                    <td data-label="#"><?= htmlspecialchars($empresa['id']); ?></td>
                                    <td data-label="Empresa"><?= htmlspecialchars($empresa['company_name']); ?></td>
                                    <td data-label="CNPJ"><?= htmlspecialchars($empresa['cnpj'] ?? 'N/A'); ?></td>
                                    <td data-label="Contato"><?= htmlspecialchars($empresa['contact'] ?? 'Nao informado'); ?></td>
                                    <td data-label="Endereço"><?= htmlspecialchars($empresa['address'] ?? 'Nao informado'); ?></td>
                                    <td data-label="Data de abertura"><?= htmlspecialchars(date('d/m/Y', strtotime($empresa['data_abertura']))) ?></td>
                                    <td data-label="Funcionários"><?= (int)($empresa['funcionarios_count'] ?? 0); ?></td> <!-- NOVO DADO -->
                                    <td data-label="Ações">
                                        <a href="#" onclick="openEditModal(<?= $empresa['id']; ?>)" title="Editar empresa">
                                            <i class="icon-edit"></i>
                                        </a>
                                        <button class="btn-inativar" onclick="openConfirmDeleteModal(<?= $empresa['id']; ?>, '<?= htmlspecialchars($empresa['company_name'], ENT_QUOTES, 'UTF-8'); ?>')" title="Excluir empresa">✕</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Nenhuma empresa cadastrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="addEmpresaModal" class="modal modal-edit-and-create" role="dialog" aria-labelledby="addModalTitle" aria-modal="true">
        <div class="modal-content">
            <form method="post" action="<?= BASE_URL; ?>index.php?url=Adm/createEmpresa" class="modal-form">
                <span class="close-button" onclick="closeAddModal()">&times;</span>
                <h2 id="addModalTitle">Cadastrar empresa</h2>
                <div id="add_modal_message_area"></div>
                <div>
                    <label for="add_company_name">Nome da Empresa</label>
                    <input type="text" id="add_company_name" name="company_name" placeholder="Nome da Empresa" required maxlength="100">
                </div>
                <div>
                    <label for="add_cnpj">CNPJ</label>
                    <input type="text" id="add_cnpj" name="cnpj" placeholder="CNPJ da Empresa" required pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" title="Formato: 00.000.000/0000-00" maxlength="18">
                </div>
                <div>
                    <label for="add_contact">Contato</label>
                    <input type="text" id="add_contact" name="contact" placeholder="Contato da Empresa" required maxlength="50">
                </div>
                <div>
                    <label for="add_address">Endereço</label>
                    <input type="text" id="add_address" name="address" placeholder="Endereço da Empresa" required maxlength="150">
                </div>
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <div id="editEmpresaModal" class="modal" role="dialog" aria-labelledby="editModalTitle" aria-modal="true">
        <div class="modal-content">
            <form method="post" action="<?= BASE_URL; ?>index.php?url=Adm/updateEmpresa" class="modal-form">
                <span class="close-button" onclick="closeEditModal()">&times;</span>
                <h2 id="editModalTitle">Editar Empresa</h2>
                <div id="edit_modal_message_area"></div>
                <input type="hidden" id="edit_id" name="id">
                <div>
                    <label for="edit_company_name">Nome da Empresa</label>
                    <input type="text" id="edit_company_name" name="company_name" required maxlength="100">
                </div>
                <div>
                    <label for="edit_cnpj">CNPJ</label>
                    <input type="text" id="edit_cnpj" name="cnpj" required maxlength="18">
                </div>
                <div>
                    <label for="edit_contact">Contato</label>
                    <input type="text" id="edit_contact" name="contact" required maxlength="50">
                </div>
                <div>
                    <label for="edit_address">Endereço</label>
                    <input type="text" id="edit_address" name="address" required maxlength="150">
                </div>
                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <div id="confirmActionModal" class="modal" role="dialog" aria-labelledby="confirmModalTitle" aria-modal="true">
        <div class="modal-content">
            <span class="close-button" onclick="closeConfirmModal()">&times;</span>
            <h2 id="confirmModalTitle"></h2>
            <div class="modal-message" id="confirmModalMessage"></div>
            <div class="confirm-buttons">
                <button class="btn-confirm-yes" id="confirmActionBtn">Sim</button>
                <button class="btn-confirm-no" type="button" onclick="closeConfirmModal()">Nao, Cancelar</button>
            </div>
        </div>
    </div>

    <div id="globalToast" class="toast-message"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        const addModal = document.getElementById("addEmpresaModal");
        const editModal = document.getElementById("editEmpresaModal");
        const confirmModal = document.getElementById("confirmActionModal");
        const confirmModalTitle = document.getElementById("confirmModalTitle");
        const confirmModalMessage = document.getElementById("confirmModalMessage");
        const confirmActionBtn = document.getElementById("confirmActionBtn");

        let currentActionFuncionarioId = null;
        let currentActionType = '';
        let currentEmpresaId = null;

        const addModalMessageArea = document.getElementById('add_modal_message_area');
        const editModalMessageArea = document.getElementById('edit_modal_message_area');

        function showModalMessage(messageAreaElement, message, isSuccess) {
            messageAreaElement.innerHTML = '';
            let messageDiv = document.createElement('div');
            messageDiv.className = 'modal-message';
            messageDiv.textContent = message;
            messageDiv.classList.toggle('success', isSuccess);
            messageDiv.style.display = 'block';
            messageDiv.style.color = isSuccess ? '#ccffcc' : '#ffcccc';
            messageDiv.style.backgroundColor = isSuccess ? '#0a0' : '#a00';
            messageAreaElement.appendChild(messageDiv);
            setTimeout(() => {
                messageDiv.style.display = 'none';
                messageDiv.textContent = '';
                messageAreaElement.innerHTML = '';
            }, 5000);
        }

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

        function openAddModal() {
            addModal.style.display = "flex";
            if (!window.autoOpeningAddModal) {
                addModal.querySelector('form').reset();
                addModalMessageArea.innerHTML = '';
            }
            window.autoOpeningAddModal = false;
        }

        function closeAddModal() {
            addModal.style.display = "none";
            addModal.querySelector('form').reset();
        }

        function openEditModal(id) {
            editModal.querySelector('form').reset();
            editModalMessageArea.innerHTML = '';
            if (typeof jQuery !== 'undefined') {
                $.ajax({
                    url: `<?= BASE_URL; ?>index.php?url=Adm/getEmpresaJson/${id}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.id) {
                            $('#edit_id').val(response.id);
                            $('#edit_company_name').val(response.company_name);
                            let cnpj = (response.cnpj || '').replace(/\D/g, '');
                            if (cnpj.length === 14) {
                                cnpj = cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
                            }
                            $('#edit_cnpj').val(cnpj);
                            $('#edit_cnpj').unmask && $('#edit_cnpj').unmask();
                            $('#edit_cnpj').mask('00.000.000/0000-00', {reverse: false});
                            let contato = (response.contact || '').replace(/\D/g, '');
                            if (contato.length === 11) {
                                contato = contato.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
                            } else if (contato.length === 10) {
                                contato = contato.replace(/^(\d{2})(\d{4})(\d{4})$/, "($1) $2-$3");
                            }
                            $('#edit_contact').val(contato);
                            $('#edit_contact').unmask && $('#edit_contact').unmask();
                            $('#edit_contact').mask('(00) 00000-0000');
                            $('#edit_address').val(response.address);
                            editModal.style.display = "flex";
                        } else {
                            alert('Empresa não encontrada ou erro ao carregar dados.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao carregar dados da empresa:', error);
                        alert('Erro ao carregar dados da empresa.');
                    }
                });
            } else {
                alert('Funcionalidade de edição requer jQuery.');
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
                confirmModalTitle.textContent = "Confirmar Inativacao";
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
            if (currentActionType === 'excluir_empresa' && currentEmpresaId) {
                const actionUrl = `<?= BASE_URL; ?>index.php?url=Adm/excluirEmpresa/${currentEmpresaId}`;
                if (typeof jQuery !== 'undefined') {
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
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                confirmModalMessage.classList.remove('success');
                                confirmModalMessage.style.color = '#ffcccc';
                                confirmModalMessage.style.backgroundColor = '#a00';
                            }
                            $('.confirm-buttons').hide();
                        },
                        error: function(xhr, status, error) {
                            confirmModalMessage.textContent = 'Erro ao excluir empresa.';
                            confirmModalMessage.classList.remove('success');
                            confirmModalMessage.style.color = '#ffcccc';
                            confirmModalMessage.style.backgroundColor = '#a00';
                            $('.confirm-buttons').hide();
                        }
                    });
                }
                return;
            }
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
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            confirmModalMessage.textContent = response.message;
                            if (response.success) {
                                confirmModalMessage.classList.add('success');
                                confirmModalMessage.style.color = '#ccffcc';
                                confirmModalMessage.style.backgroundColor = '#0a0';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
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

        function openConfirmDeleteModal(id, name) {
            currentEmpresaId = id;
            currentActionType = 'excluir_empresa';
            confirmModalTitle.textContent = "Confirmar Exclusão";
            confirmModalMessage.innerHTML = `Tem certeza que deseja <strong>excluir</strong> a empresa <strong>${name}</strong>?<br>Esta ação não poderá ser desfeita.`;
            confirmActionBtn.textContent = "Sim, Excluir";
            confirmActionBtn.className = "btn-confirm-yes btn-inativar";
            confirmModal.style.display = "flex";
            $('.confirm-buttons').show();
        }

        <?php if (isset($viewData['error_message']) || isset($viewData['success_message'])): ?>
            <?php if (isset($viewData['success_message'])): ?>
                displayToast('<?= htmlspecialchars($viewData["success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
            <?php else: ?>
                window.autoOpeningAddModal = true;
                openAddModal();
                showModalMessage(addModalMessageArea, '<?= htmlspecialchars($viewData["error_message"], ENT_QUOTES, 'UTF-8'); ?>', false);
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($viewData['edit_error_message']) || isset($viewData['edit_success_message'])): ?>
            <?php if (isset($viewData['edit_success_message'])): ?>
                displayToast('<?= htmlspecialchars($viewData["edit_success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
            <?php else: ?>
                window.autoOpeningEditModal = true;
                const editingFuncionarioId = <?= isset($viewData['editing_funcionario_id']) ? $viewData['editing_funcionario_id'] : 'null'; ?>;
                if (editingFuncionarioId) {
                    openEditModal(editingFuncionarioId);
                    showModalMessage(editModalMessageArea, '<?= htmlspecialchars($viewData["edit_error_message"], ENT_QUOTES, 'UTF-8'); ?>', false);
                } else {
                    editModal.style.display = "flex";
                    showModalMessage(editModalMessageArea, '<?= htmlspecialchars($viewData["edit_error_message"], ENT_QUOTES, 'UTF-8'); ?>', false);
                }
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($viewData['inativar_error_message']) || isset($viewData['inativar_success_message']) || isset($viewData['ativar_error_message']) || isset($viewData['ativar_success_message'])): ?>
            confirmModal.style.display = "flex";
            const inativateMessageDiv = $('#confirmModalMessage');
            <?php if (isset($viewData['inativar_error_message'])): ?>
                confirmModalTitle.textContent = "Erro na Inativacao";
                inativateMessageDiv.text('<?= htmlspecialchars($viewData["inativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>');
                inativateMessageDiv.removeClass('success').css({
                    'color': 'red',
                    'background-color': '#a00'
                });
            <?php elseif (isset($viewData['inativar_success_message'])): ?>
                confirmModalTitle.textContent = "Inativacao Realizada";
                inativateMessageDiv.html('<span style="color:green;">' + '<?= htmlspecialchars($viewData["inativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>' + '</span>');
                inativateMessageDiv.addClass('success').css({
                    'color': 'green',
                    'background-color': '#0a0'
                });
            <?php elseif (isset($viewData['ativar_error_message'])): ?>
                confirmModalTitle.textContent = "Erro na Ativacao";
                inativateMessageDiv.text('<?= htmlspecialchars($viewData["ativar_error_message"], ENT_QUOTES, 'UTF-8'); ?>');
                inativateMessageDiv.removeClass('success').css({
                    'color': 'red',
                    'background-color': '#a00'
                });
            <?php elseif (isset($viewData['ativar_success_message'])): ?>
                confirmModalTitle.textContent = "Ativacao Realizada";
                inativateMessageDiv.html('<span style="color:green;">' + '<?= htmlspecialchars($viewData["ativar_success_message"], ENT_QUOTES, 'UTF-8'); ?>' + '</span>');
                inativateMessageDiv.addClass('success').css({
                    'color': 'green',
                    'background-color': '#0a0'
                });
            <?php endif; ?>
            $('.confirm-buttons').hide();
            <?php if (isset($viewData['inativar_success_message']) || isset($viewData['ativar_success_message'])): ?>
                setTimeout(() => {
                    closeConfirmModal();
                }, 3000);
            <?php endif; ?>
        <?php endif; ?>

        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                $('#add_cpf').mask('000.000.000-00', {reverse: false});
                $('#edit_cpf').mask('000.000.000-00', {reverse: false});
            });
        }
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                $('#add_cnpj').mask('00.000.000/0000-00', {reverse: false});
                $('#edit_cnpj').mask('00.000.000/0000-00', {reverse: false});
                $('#add_contact').mask('(00) 00000-0000');
                $('#edit_contact').mask('(00) 00000-0000');
            });
        }
        <?php if (isset($viewData['error_message']) || isset($viewData['success_message'])): ?>
            <?php if (isset($viewData['success_message'])): ?>
                displayToast('<?= htmlspecialchars($viewData["success_message"], ENT_QUOTES, 'UTF-8'); ?>', 'success');
            <?php else: ?>
                window.autoOpeningAddModal = true;
                openAddModal();
                showModalMessage(addModalMessageArea, '<?= htmlspecialchars($viewData["error_message"], ENT_QUOTES, 'UTF-8'); ?>', false);
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>

</html>