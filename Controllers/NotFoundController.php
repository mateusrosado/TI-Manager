<?php
// C:\xampp\htdocs\TI-Manager\Controllers\NotFoundController.php

class NotFoundController {

    public function index() {
        // Aqui você pode carregar uma view de erro 404
        // Exemplo:
        // $view = new View(); // Se você tiver uma classe View
        // $view->loadTemplate('404');

        // Por enquanto, para vermos que está funcionando, vamos apenas exibir uma mensagem:
        echo "<h1>Erro 404 - Página Não Encontrada</h1>";
        echo "<p>A URL que você tentou acessar não existe.</p>";
        // Você pode adicionar um link para a página inicial
        echo "<p><a href='" . BASE_URL . "'>Voltar para a Página Inicial</a></p>";
    }
}
?>