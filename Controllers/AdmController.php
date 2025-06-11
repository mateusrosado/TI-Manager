<?php

class AdmController extends Controller
{
    private $admModel;
    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->admModel = new AdmModel();
        $this->session = new Session();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login/adm');
            exit();
        }

        $cnpj = $_POST['cnpj'] ?? '';
        $password = $_POST['password'] ?? '';

        $cnpj = htmlspecialchars(trim($cnpj));
        $password = htmlspecialchars(trim($password));

        if (empty($cnpj) || empty($password)) {
            $this->session->set('login_error', 'CNPJ e senha são obrigatórios.');
            header('Location: ' . BASE_URL . 'login/adm');
            exit();
        }

        $cnpjNumerico = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpjNumerico) !== 14 || !is_numeric($cnpjNumerico)) {
            $this->session->set('login_error', 'Formato de CNPJ inválido. Use apenas números ou o formato 00.000.000/0000-00.');
            header('Location: ' . BASE_URL . 'login/adm');
            exit();
        }

        if (strlen($password) < 6) {
            $this->session->set('login_error', 'A senha deve ter pelo menos 6 caracteres.');
            header('Location: ' . BASE_URL . 'login/adm');
            exit();
        }

        $user = $this->admModel->findByCnpjAndVerifyPassword($cnpjNumerico, $password);

        if ($user) {
            $this->session->setUser($user);
            $this->redirectToView($user['role']);
        } else {
            $this->session->set('login_error', 'CNPJ ou senha inválidos.');
            header('Location: ' . BASE_URL . 'login/adm');
            exit();
        }
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: ' . BASE_URL . 'login/adm');
        exit();
    }
}
