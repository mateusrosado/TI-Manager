<?php
// File: TI-MANAGER/Controllers/NotFoundController.php

class NotFoundController extends Controller {
    /**
     * Método padrão para exibir a página 404.
     */
    public function index() {
        http_response_code(404); // Define o código de status HTTP para 404
        echo "<h1>404 - Página Não Encontrada</h1>";
        echo "<p>A rota que você tentou acessar não existe ou não foi configurada.</p>";
        // Você pode carregar uma view de erro mais elaborada aqui:
        // $this->loadView('404');
    }
}
