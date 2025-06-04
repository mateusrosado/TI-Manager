<?php
// C:\xampp\htdocs\TI-Manager\Controllers\LoginController.php

class LoginController extends Controller { // Confirme se você está estendendo a classe Controller
    public function index() {
        // Remove os 'echo' de teste.
        // echo "<h1>Bem-vindo à Página de Login!</h1>";
        // echo "<p>Agora precisamos carregar sua view de login da pasta Views/Login.</p>";

        // Agora, vamos carregar a view de login.
        // O caminho para Views/Login/index.php seria 'Login/index'.
        $this->loadView('Login/index'); // Chame o método loadView da classe pai (Controller)
    }

    // Outros métodos de login, se houver
}
?>