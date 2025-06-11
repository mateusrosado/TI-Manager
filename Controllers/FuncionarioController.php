<?php
// File: TI-MANAGER/Controllers/FuncionarioController.php

class FuncionarioController extends Controller
{
    private $funcionarioModel; // Instância do modelo de funcionário (para login)
    private $session;          // Instância do gerenciador de sessão
    // private $chamadoModel; // Você pode precisar de um ChamadoModel para buscar chamados

    public function __construct()
    {
        parent::__construct(); // Chama o construtor da classe base Controller
        $this->funcionarioModel = new FuncionarioModel(); // Instancia o FuncionarioModel
        $this->session = new Session();                  // Instancia a classe Session
        // $this->chamadoModel = new ChamadoModel(); // Exemplo: se precisar de um modelo para chamados
    }

    /**
     * Processa a requisição de login para FuncionarioTI/FuncionarioCliente.
     * Esta ação é chamada quando o formulário em 'Views/Login/funcionario.php' é submetido.
     * Rota de POST: index.php?url=Funcionario/login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $email = htmlspecialchars(trim($email));
        $password = htmlspecialchars(trim($password));

        if (empty($email) || empty($password)) {
            $this->session->set('login_error', 'Email e senha são obrigatórios.');
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set('login_error', 'Formato de email inválido.');
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }

        if (strlen($password) < 6) {
            $this->session->set('login_error', 'A senha deve ter pelo menos 6 caracteres.');
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }

        $user = $this->funcionarioModel->findByEmailAndVerifyPassword($email, $password);

        if ($user) {
            $this->session->setUser($user);
            // Redireciona para a página 'Chamados' usando o método da classe base Controller
            $this->redirectToView($user['role']);
        } else {
            $this->session->set('login_error', 'Email ou senha inválidos.');
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }
    }

    /**
     * Realiza o logout do usuário FuncionarioTI ou FuncionarioCliente.
     * Rota: index.php?url=Funcionario/logout
     */
    public function logout()
    {
        $this->session->destroy();
        header('Location: ' . BASE_URL . 'index.php?url=login/funcionario'); // Redireciona para o login de funcionário
        exit();
    }

    /**
     * Exibe a página 'Chamados' para usuários 'funcionario_ti' e 'funcionario_cliente'.
     * Esta é a ação que será chamada após um login bem-sucedido ou ao acessar a rota 'Chamados/index'.
     * Rota: index.php?url=Chamados/index
     */
    public function chamados() {
        // 1. Verificação de autenticação e papel (Proteção de rota)
        if (!$this->session->isLoggedIn()) {
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }

        $userRole = $this->session->get('user_role');
        $userName = $this->session->get('user_name');
        $userId = $this->session->get('user_id');

        // Garante que apenas 'funcionario_ti' ou 'funcionario_cliente' acessem esta página
        if (!in_array($userRole, ['funcionario_ti', 'funcionario_cliente'])) {
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario'); // Ou para um NotFoundController/AccessDenied
            exit();
        }

        // 2. Prepara os dados a serem passados para a view
        $viewData = [
            'name' => $userName,
            'user_role' => $userRole,
            'user_id' => $userId,
            'nivel-1' => 'Chamados', // Marca o item "Chamados" na sidebar como ativo
            // ... quaisquer outros dados específicos da página de Chamados
            // (ex: lista de chamados, status, etc. - você precisaria de um ChamadoModel para isso)
        ];

        // 3. Carrega a view 'Views/Chamados/index.php' que serve como a página de Chamados para funcionários
        // Você precisará criar esta view.
        $this->loadView('Chamados/index', $viewData);
    }
}
