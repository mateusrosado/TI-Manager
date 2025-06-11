<?php
// C:\xampp\htdocs\TI-Manager\Controllers\LoginController.php

class LoginController extends Controller {
    public function adm() {
        $this->loadView('Login/Adm');
    }
    public function funcionario() {
        $this->loadView('Login/funcionario');
    }
}
