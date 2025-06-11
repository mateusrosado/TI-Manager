<?php

class FuncionarioController extends Controller
{
    private $funcionarioModel;
    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->funcionarioModel = new FuncionarioModel();
        $this->session = new Session();
    }

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
            $this->redirectToView($user['role']);
        } else {
            $this->session->set('login_error', 'Email ou senha inválidos.');
            header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
            exit();
        }
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: ' . BASE_URL . 'index.php?url=login/funcionario');
        exit();
    }
}
