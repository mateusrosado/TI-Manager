<?php
// C:\xampp\htdocs\TI-Manager\Controllers\HomeController.php

class HomeController extends Controller {
    public function index() {
        // Este método será responsável por carregar a view da página inicial (Home).
        // Ele chamará o arquivo Views/Home/index.php.
        $this->loadView('Home/index');
    }

    // Se você tiver outras ações na página Home (ex: um botão de logout, ou uma dashboard específica),
    // você pode criar outros métodos aqui, como 'logout()' ou 'dashboard()'.
    // Ex: public function dashboard() { $this->loadView('Home/dashboard'); }
}
?>