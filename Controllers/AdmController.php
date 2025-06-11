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

        $cnpj = htmlspecialchars(trim($_POST['cnpj'] ?? ''));
        $password = htmlspecialchars(trim($_POST['password'] ?? ''));

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

        if (strlen($password) < 6) {
            $this->session->set('login_error', 'A senha deve ter pelo menos 6 caracteres.');
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

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'user_id' => $userId,
            'nivel-1' => 'Home',
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
        $funcionariosData = $this->userModel->getFuncionariosWithCompanyInfo();

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Funcionarios',
            'funcionarios' => $funcionariosData,
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
        $empresasData = $this->clientModel->getAllClients();

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Empresas',
            'empresas' => $empresasData,
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
        $historicoData = $this->ticketModel->getAllTicketsWithDetails();

        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'nivel-1' => 'Historico',
            'historico' => $historicoData,
        ];

        $this->loadView('Adm/historico', $viewData);
    }
}
