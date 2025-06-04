<?php
// C:\xampp\htdocs\TI-Manager\Controllers\SendEmailController.php

class SendEmailController extends Controller { // Lembre-se de estender a classe Controller
    public function index() {
        // Este método será responsável por carregar a view de envio de e-mail/recuperação de senha.
        // Para isso, usaremos o método loadView que você tem em Core/Controller.php

        // O caminho para a view seria 'SendEmail/index'
        // Assumindo que você tem um arquivo Views/SendEmail/index.php
        $this->loadView('SendEmail/index');

        // Se você tiver uma lógica para processar o formulário de envio de e-mail,
        // pode ser outro método aqui, ex: public function send() { ... }
    }
}
?>