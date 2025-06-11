<?php
// File: TI-MANAGER/Controllers/LoginController.php

class LoginController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->adm();
    }

    public function adm() {
        $session = new Session();
        if ($session->isLoggedIn()) {
            $this->redirectToDashboard($session->get('user_role'));
        }
        $this->loadView('Login/adm');
    }

    public function funcionario() {
        $session = new Session();
        if ($session->isLoggedIn()) {
            $this->redirectToDashboard($session->get('user_role'));
        }
        $this->loadView('Login/funcionario');
    }
}
