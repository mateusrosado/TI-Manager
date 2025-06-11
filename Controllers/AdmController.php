<?php
// File: TI-MANAGER/Controllers/AdmController.php

class AdmController extends Controller
{
    private $admModel;     // Instância do modelo de ADM (para login)
    private $session;      // Instância do gerenciador de sessão
    private $userModel;    // Instância do UserModel (para buscar dados de funcionários)
    private $clientModel;  // Instância do ClientModel
    private $ticketModel;  // Instância do TicketModel

    public function __construct()
    {
        parent::__construct(); // Chama o construtor da classe base Controller
        $this->admModel = new AdmModel(); // Instancia o AdmModel (para login)
        $this->session = new Session();   // Instancia a classe Session
        $this->userModel = new UserModel(); // Instancia o UserModel (para operações gerais de usuário, incluindo buscar funcionários)
        $this->clientModel = new ClienteModel(); // Instancia o ClientModel
        $this->ticketModel = new TicketModel(); // Instancia o TicketModel
    }

    /**
     * Processa a requisição de login para Adm/AdmCliente.
     * Rota de POST: index.php?url=Adm/login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm'); // Redireciona para o formulário GET
            exit();
        }

        $cnpj = $_POST['cnpj'] ?? '';
        $password = $_POST['password'] ?? '';

        // Limpeza e normalização dos dados de entrada
        $cnpj = htmlspecialchars(trim($cnpj));
        $password = htmlspecialchars(trim($password));

        // --- Validações de Entrada ---
        if (empty($cnpj) || empty($password)) {
            $this->session->set('login_error', 'CNPJ e senha são obrigatórios.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        // Validação do formato do CNPJ (apenas 14 dígitos numéricos)
        $cnpjNumerico = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpjNumerico) !== 14 || !is_numeric($cnpjNumerico)) {
            $this->session->set('login_error', 'Formato de CNPJ inválido. Use apenas números ou o formato 00.000.000/0000-00.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        // Validação do comprimento mínimo da senha (ex: 6 caracteres)
        if (strlen($password) < CONF_PASSWD_MIN_LEN) {
            $this->session->set('login_error', 'A senha deve ter no mínimo ' . CONF_PASSWD_MIN_LEN . ' caracteres.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        // Tenta autenticar o usuário através do modelo
        $user = $this->admModel->findByCnpjAndVerifyPassword($cnpjNumerico, $password);

        if ($user) {
            // Login bem-sucedido: armazena dados do usuário na sessão e redireciona
            $this->session->setUser($user);
            $this->redirectToView($user['role']); // Redireciona para a página principal apropriada
        } else {
            // Login falhou: define mensagem de erro e redireciona de volta ao formulário
            $this->session->set('login_error', 'CNPJ ou senha inválidos.');
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }
    }

    /**
     * Realiza o logout do usuário ADM ou AdmCliente.
     * Rota: index.php?url=Adm/logout
     */
    public function logout()
    {
        $this->session->destroy(); // Destrói a sessão do usuário
        header('Location: ' . BASE_URL . 'index.php?url=login/adm'); // Redireciona para o login ADM após logout
        exit();
    }

    /**
     * Exibe a página 'Home' para usuários 'admin' e 'adm_cliente'.
     * Rota: index.php?url=Home/index
     */
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

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'user_id' => $userId,
            'nivel-1' => 'Home',
        ];

        $this->loadView('Adm/home', $viewData);
    }

    /**
     * Exibe a página de gerenciamento de funcionários para o ADM (apenas FuncionarioTI).
     * Rota: index.php?url=Adm/funcionarios
     */
    public function funcionarios() {
        // 1. Proteção de rota: Garante que apenas o ADM logado acesse
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userName = $this->session->get('user_name');
        $userRole = $this->session->get('user_role');

        // Obtém APENAS os funcionários de TI
        $funcionariosData = $this->userModel->getFuncionariosFiltered('funcionario_ti');
        
        // Obtém a lista de empresas para o dropdown do modal
        $empresas = $this->clientModel->getAllClients();

        // Recupera mensagens de erro ou sucesso da sessão (após submissão do modal)
        $errorMessage = $this->session->get('error_message');
        $this->session->remove('error_message');
        $successMessage = $this->session->get('success_message');
        $this->session->remove('success_message');

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Funcionarios',
            'funcionarios' => $funcionariosData,
            'empresas' => $empresas, // Passa as empresas para o select do modal
            'error_message' => $errorMessage, // Passa a mensagem de erro para a view
            'success_message' => $successMessage, // Passa a mensagem de sucesso para a view
        ];

        // Carrega a view 'Adm/funcionarios.php', que agora contém o HTML do modal
        $this->loadView('Adm/funcionarios', $viewData);
    }

    /**
     * Exibe a página de gerenciamento de empresas para o ADM.
     * Rota: index.php?url=Adm/empresas
     */
    public function empresas() {
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userName = $this->session->get('user_name');
        $userRole = $this->session->get('user_role');

        $empresasData = $this->clientModel->getAllClients();

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Empresas',
            'empresas' => $empresasData,
        ];

        $this->loadView('Adm/empresas', $viewData);
    }

    /**
     * Exibe a página de histórico geral de chamados para o ADM.
     * Rota: index.php?url=Adm/historico
     */
    public function historico() {
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        $userName = $this->session->get('user_name');
        $userRole = $this->session->get('user_role');

        $historicoData = $this->ticketModel->getAllTicketsWithDetails();

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Historico',
            'historico' => $historicoData,
        ];

        $this->loadView('Adm/historico', $viewData);
    }

    // O método 'addFuncionario()' que carregava o modal como página separada foi removido daqui,
    // pois o modal agora é parte da view 'funcionarios.php'.

    /**
     * Processa a submissão do formulário de cadastro de Funcionário TI.
     * Rota de POST: index.php?url=Adm/createFuncionarioTI
     */
    public function createFuncionarioTI() {
        // Garante que a requisição seja um POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios'); // Redireciona de volta para a lista
            exit();
        }

        // Proteção de rota: Apenas o ADM (dono) pode acessar
        if (!$this->session->isLoggedIn() || $this->session->get('user_role') !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?url=login/adm');
            exit();
        }

        // Coleta e limpa os dados do formulário
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $cpf = htmlspecialchars(trim($_POST['cpf'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? ''; // Senha em texto puro para hash
        $funcao = htmlspecialchars(trim($_POST['funcao'] ?? ''));
        // Certifica-se de que client_id é um inteiro ou null
        $client_id = !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null;

        $errors = [];

        // --- Validações do Formulário ---
        if (empty($name) || empty($cpf) || empty($email) || empty($password) || empty($funcao)) {
            $errors[] = 'Todos os campos obrigatórios (Nome, CPF, Email, Senha, Função) devem ser preenchidos.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Formato de email inválido.';
        }
        if (strlen($password) < CONF_PASSWD_MIN_LEN) {
            $errors[] = 'A senha deve ter no mínimo ' . CONF_PASSWD_MIN_LEN . ' caracteres.';
        }
        // Validação de CPF (básica, pode ser mais robusta)
        $cpfNumerico = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpfNumerico) !== 11 || !is_numeric($cpfNumerico)) {
            $errors[] = 'Formato de CPF inválido. Use apenas números e o formato 000.000.000-00.';
        }

        // Verifica se o email ou CPF já existem (no UserModel)
        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Este email já está cadastrado.';
        }
        if ($this->userModel->findByCpf($cpfNumerico)) {
            $errors[] = 'Este CPF já está cadastrado.';
        }

        if (empty($errors)) {
            // Se não houver erros de validação, procede com o cadastro
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'funcionario_ti'; // O papel fixo para este formulário

            // O UserModel cuidará de inserir o usuário e o registro em 'employees'
            $newUserId = $this->userModel->createFuncionarioTI(
                $name,
                $cpfNumerico,
                $email,
                $hashedPassword,
                $role,
                $funcao, // Passando a função
                $client_id // Passa o ID da empresa ou null
            );

            if ($newUserId) {
                $this->session->set('success_message', 'Funcionário TI cadastrado com sucesso!');
                header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios'); // Redireciona para a lista
                exit();
            } else {
                $errors[] = 'Erro ao cadastrar funcionário. Tente novamente.';
            }
        }

        // Se houver erros, armazena na sessão e redireciona de volta para a lista de funcionários
        $this->session->set('error_message', implode('<br>', $errors));
        header('Location: ' . BASE_URL . 'index.php?url=Adm/funcionarios');
        exit();
    }
}
