<?php
// C:\xampp\htdocs\TI-Manager\Controllers\LoginController.php

class LoginController extends Controller {
    public function adm() {
        $this->loadView('Login/loginAdm');
    }
    public function funcionario() {
        $this->loadView('Login/loginFuncionario');
    }
    
    public function loginAdm() {
        $this->loadView('Adm/home');
    }

}
