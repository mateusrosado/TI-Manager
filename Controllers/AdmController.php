<?php

class AdmController extends Controller
{
    private $admModel;
    private $session;
    private $userModel;
    private $clientModel;
    private $ticketModel;

    public function __construct()
    {
        parent::__construct();
        $this->admModel = new AdmModel();
        $this->session = new Session();
        $this->userModel = new UserModel();
        $this->clientModel = new ClienteModel();
        $this->ticketModel = new TicketModel();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $cnpj = $_POST['cnpj'] ?? '';
        $password = $_POST['password'] ?? '';

        $cnpj = htmlspecialchars(trim($cnpj));
        $password = htmlspecialchars(trim($password));

        if (empty($cnpj) || empty($password)) {
            $this->session->set('login_error', 'CNPJ e senha são obrigatórios.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $cnpjNumerico = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpjNumerico) !== 14 || !is_numeric($cnpjNumerico)) {
            $this->session->set('login_error', 'Formato de CNPJ inválido. Use apenas números ou o formato 00.000.000/0000-00.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        if (strlen($password) < CONF_PASSWD_MIN_LEN) {
            $this->session->set('login_error', 'A senha deve ter no mínimo ' . CONF_PASSWD_MIN_LEN . ' caracteres.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $user = $this->admModel->findByCnpjAndVerifyPassword($cnpjNumerico, $password);

        if ($user) {
            $this->session->setUser($user);
            $this->redirectToView($user['role']);
        } else {
            $this->session->set('login_error', 'CNPJ ou senha inválidos.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: ' . BASE_URL . 'index.php?url=login/adm');
        exit();
    }

    public function home() {
        if (!$this->session->isLoggedIn()) {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userRole = $this->session->get('user_role');
        $userName = $this->session->get('user_name');
        $userId = $this->session->get('user_id');

        if (!in_array($userRole, ['admin', 'adm_cliente'])) {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $empresas = $this->clientModel->getAllClients(); // <-- Adicione esta linha

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'user_id' => $userId,
            'nivel-1' => 'Home',
            'empresas' => $empresas, // <-- E esta linha
        ];

        $this->loadView('Adm/home', $viewData);
    }

    public function funcionarios() {
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userName = $this->session->get('user_name');
        $userRole = $this->session->get('user_role');

        $funcionariosData = $this->userModel->getFuncionariosFiltered('funcionario_ti');
        $empresas = $this->clientModel->getAllClients();

        $errorMessage = $this->session->get('error_message');
        $this->session->remove('error_message');
        $successMessage = $this->session->get('success_message');
        $this->session->remove('success_message');

        $editErrorMessage = $this->session->get('edit_error_message');
        $this->session->remove('edit_error_message');
        $editSuccessMessage = $this->session->get('edit_success_message');
        $this->session->remove('edit_success_message');
        $editingFuncionarioId = $this->session->get('editing_funcionario_id');
        $this->session->remove('editing_funcionario_id');

        $inativarErrorMessage = $this->session->get('inativar_error_message');
        $this->session->remove('inativar_error_message');
        $inativarSuccessMessage = $this->session->get('inativar_success_message');
        $this->session->remove('inativar_success_message');

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Funcionarios',
            'funcionarios' => $funcionariosData,
            'empresas' => $empresas,
            'error_message' => $errorMessage,
            'success_message' => $successMessage,
            'edit_error_message' => $editErrorMessage,
            'edit_success_message' => $editSuccessMessage,
            'editing_funcionario_id' => $editingFuncionarioId,
            'inativar_error_message' => $inativarErrorMessage,
            'inativar_success_message' => $inativarSuccessMessage,
        ];

        $this->loadView('Adm/funcionarios', $viewData);
    }

    public function empresas() {
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userName = $this->session->get('user_name');
        $userRole = $this->session->get('user_role');
        $userId = $this->session->get('user_id');

        $empresasData = $this->clientModel->getAllClients();

        $errorMessage = $this->session->get('empresa_error_message');
        $this->session->remove('empresa_error_message');
        $successMessage = $this->session->get('empresa_success_message');
        $this->session->remove('empresa_success_message');

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'user_id' => $userId,
            'nivel-1' => 'Empresas',
            'empresas' => $empresasData,
            'error_message' => $errorMessage,
            'success_message' => $successMessage,
        ];

        $this->loadView('Adm/empresas', $viewData);
    }

    public function historico() {
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userName = $this->session->get('user_name');
        $userRole = $this->session->get('user_role');

        $empresaId = isset($_GET['empresa']) ? (int)$_GET['empresa'] : null;

        $chamados = [];
        $empresa = null;
        if ($empresaId) {
            $chamados = $this->ticketModel->getTicketsByEmpresaId($empresaId);
            $empresa = $this->clientModel->getEmpresaById($empresaId);
        }

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'empresa' => $empresa,
            'chamados' => $chamados,
        ];

        $this->loadView('Adm/historico', $viewData);
    }

    public function createFuncionarioTI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
            exit();
        }
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $cpf = htmlspecialchars(trim($_POST['cpf'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $funcao = htmlspecialchars(trim($_POST['funcao'] ?? ''));
        $client_id = !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null;

        $errors = [];

        if (empty($name) || empty($cpf) || empty($email) || empty($password) || empty($funcao)) {
            $errors[] = 'Todos os campos obrigatórios (Nome, CPF, Email, Senha, Função) devem ser preenchidos.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Formato de email inválido.';
        }
        if (strlen($password) < CONF_PASSWD_MIN_LEN) {
            $errors[] = 'A senha deve ter no mínimo ' . CONF_PASSWD_MIN_LEN . ' caracteres.';
        }
        $cpfNumerico = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpfNumerico) !== 11 || !is_numeric($cpfNumerico)) {
            $errors[] = 'Formato de CPF inválido. Use apenas números e o formato 000.000.000-00.';
        }

        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Este email já está cadastrado.';
        }
        if ($this->userModel->findByCpf($cpfNumerico)) {
            $errors[] = 'Este CPF já está cadastrado.';
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'funcionario_ti';

            $newUserId = $this->userModel->createFuncionarioTI(
                $name, $cpfNumerico, $email, $hashedPassword, $role, $funcao, $client_id
            );

            if ($newUserId) {
                $this->session->set('success_message', 'Funcionário TI cadastrado com sucesso!');
                header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
                exit();
            } else {
                $errors[] = 'Erro ao cadastrar funcionário. Tente novamente.';
            }
        }

        $this->session->set('error_message', implode('<br>', $errors));
        header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
        exit();
    }

    public function getFuncionarioTIJson(int $id) {
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado.']);
            exit();
        }

        header('Content-Type: application/json');

        $funcionario = $this->userModel->getFuncionarioById($id);

        if ($funcionario) {
            unset($funcionario['password']);
            echo json_encode($funcionario);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Funcionário não encontrado.']);
        }
        exit();
    }

    public function getEmpresaJson($id)
    {
        header('Content-Type: application/json');
        $empresa = $this->clientModel->getEmpresaById((int)$id);
        if ($empresa) {
            echo json_encode($empresa);
        } else {
            echo json_encode(['error' => 'Empresa não encontrada']);
        }
        exit;
    }

    public function updateFuncionarioTI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
            exit();
        }

        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $cpf = htmlspecialchars(trim($_POST['cpf'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $funcao = htmlspecialchars(trim($_POST['funcao'] ?? ''));
        $client_id = !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null;

        $errors = [];

        if ($id <= 0) {
            $errors[] = 'ID do funcionário inválido para edição.';
        }
        if (empty($name) || empty($cpf) || empty($email) || empty($funcao)) {
            $errors[] = 'Campos obrigatórios (Nome, CPF, Email, Função) devem ser preenchidos.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Formato de email inválido.';
        }
        if (!empty($password) && strlen($password) < CONF_PASSWD_MIN_LEN) {
            $errors[] = 'A nova senha deve ter no mínimo ' . CONF_PASSWD_MIN_LEN . ' caracteres.';
        }
        $cpfNumerico = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpfNumerico) !== 11 || !is_numeric($cpfNumerico)) {
            $errors[] = 'Formato de CPF inválido. Use apenas números e o formato 000.000.000-00.';
        }

        $existingUserByEmail = $this->userModel->findByEmail($email);
        if ($existingUserByEmail && $existingUserByEmail['id'] !== $id) {
            $errors[] = 'Este email já está cadastrado por outro usuário.';
        }
        $existingUserByCpf = $this->userModel->findByCpf($cpfNumerico);
        if ($existingUserByCpf && $existingUserByCpf['id'] !== $id) {
            $errors[] = 'Este CPF já está cadastrado por outro usuário.';
        }

        if (empty($errors)) {
            $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

            $updateSuccess = $this->userModel->updateFuncionarioTI(
                $id, $name, $cpfNumerico, $email, $hashedPassword, $funcao, $client_id
            );

            if ($updateSuccess) {
                $this->session->set('edit_success_message', 'Funcionário atualizado com sucesso!');
                header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
                exit();
            } else {
                $errors[] = 'Erro ao atualizar funcionário. Verifique os dados e tente novamente.';
            }
        }

        $this->session->set('edit_error_message', implode('<br>', $errors));
        $this->session->set('editing_funcionario_id', $id);
        header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
        exit();
    }

    public function inativarFuncionario(int $id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
            exit();
        }
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Apenas administradores podem realizar esta ação.']);
            exit();
        }

        header('Content-Type: application/json');

        $result = $this->userModel->inativarFuncionario($id);

        if ($result['success']) {
            $this->session->set('inativar_success_message', $result['message']);
        } else {
            $this->session->set('inativar_error_message', $result['message']);
        }
        
        echo json_encode($result);
        exit();
    }

    public function ativarFuncionario(int $id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
            exit();
        }
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Apenas administradores podem realizar esta ação.']);
            exit();
        }

        header('Content-Type: application/json');

        $result = $this->userModel->ativarFuncionario($id);

        if ($result['success']) {
            $this->session->set('ativar_success_message', $result['message']);
        } else {
            $this->session->set('ativar_error_message', $result['message']);
        }
        
        echo json_encode($result);
        exit();
    }

    public function createEmpresa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
            exit();
        }

        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            $this->session->set('empresa_error_message', 'Acesso negado. Apenas administradores podem cadastrar empresas.');
            header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
            exit();
        }

        $companyName = htmlspecialchars(trim($_POST['company_name'] ?? ''));
        $cnpj = htmlspecialchars(trim($_POST['cnpj'] ?? ''));
        $contact = htmlspecialchars(trim($_POST['contact'] ?? ''));
        $address = htmlspecialchars(trim($_POST['address'] ?? ''));
        $adminUserId = $this->session->get('user_id');

        $errors = [];

        if (empty($companyName) || empty($cnpj) || empty($contact) || empty($address)) {
            $errors[] = 'Todos os campos obrigatórios (Nome da Empresa, CNPJ, Contato, Endereço) devem ser preenchidos.';
        }
        $cnpjNumerico = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpjNumerico) !== 14 || !is_numeric($cnpjNumerico)) {
            $errors[] = 'Formato de CNPJ inválido. Use apenas números ou o formato 00.000.000/0000-00.';
        }

        if ($this->clientModel->findByCnpj($cnpjNumerico)) {
            $errors[] = 'Este CNPJ já está cadastrado para outra empresa.';
        }

        if (empty($errors)) {
            $newClientId = $this->clientModel->createClient(
                $companyName, $cnpjNumerico, $contact, $address, $adminUserId
            );

            if ($newClientId) {
                $this->session->set('empresa_success_message', 'Empresa cadastrada com sucesso!');
                header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
                exit();
            } else {
                $errors[] = 'Erro ao cadastrar empresa. Tente novamente.';
            }
        }

        $this->session->set('empresa_error_message', implode('<br>', $errors));
        header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
        exit();
    }

    public function excluirEmpresa($id)
    {
        header('Content-Type: application/json');
        if ($this->clientModel->excluirEmpresa((int)$id)) {
            echo json_encode(['success' => true, 'message' => 'Empresa excluída com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir empresa.']);
        }
        exit;
    }

    public function updateEmpresa()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
            exit();
        }

        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            $this->session->set('empresa_error_message', 'Acesso negado. Apenas administradores podem editar empresas.');
            header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
            exit();
        }

        $id = (int)($_POST['id'] ?? 0);
        $companyName = htmlspecialchars(trim($_POST['company_name'] ?? ''));
        $cnpj = htmlspecialchars(trim($_POST['cnpj'] ?? ''));
        $contact = htmlspecialchars(trim($_POST['contact'] ?? ''));
        $address = htmlspecialchars(trim($_POST['address'] ?? ''));

        $errors = [];

        if ($id <= 0) {
            $errors[] = 'ID da empresa inválido para edição.';
        }
        if (empty($companyName) || empty($cnpj) || empty($contact) || empty($address)) {
            $errors[] = 'Todos os campos obrigatórios devem ser preenchidos.';
        }
        $cnpjNumerico = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpjNumerico) !== 14 || !is_numeric($cnpjNumerico)) {
            $errors[] = 'Formato de CNPJ inválido. Use apenas números ou o formato 00.000.000/0000-00.';
        }

        $empresaExistente = $this->clientModel->findByCnpj($cnpjNumerico);
        if ($empresaExistente && $empresaExistente['id'] != $id) {
            $errors[] = 'Este CNPJ já está cadastrado para outra empresa.';
        }

        if (empty($errors)) {
            $atualizado = $this->clientModel->updateEmpresa($id, $companyName, $cnpjNumerico, $contact, $address);
            if ($atualizado) {
                $this->session->set('empresa_success_message', 'Empresa atualizada com sucesso!');
                header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
                exit();
            } else {
                $errors[] = 'Erro ao atualizar empresa. Tente novamente.';
            }
        }

        $this->session->set('empresa_error_message', implode('<br>', $errors));
        header('Location: ' . BASE_URL . 'index.php?url=Adm/empresas');
        exit();
    }
}
