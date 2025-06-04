<?php

// Inclua sua classe Controller base aqui, se ela estiver em um arquivo separado.
// Exemplo: require_once 'BaseController.php'; 
// ou se sua classe Controller estiver no mesmo arquivo de roteamento ou já carregada via autoloader.

class AddClienteController extends Controller { // Assumindo que 'Controller' é sua classe base

    public function index() {
        // Simplesmente carrega a view do formulário de cadastro de empresa.
        // Não há lógica de POST ou validação aqui ainda.
        $this->loadView('AddCliente/index'); 
        // O método loadView geralmente espera o nome da view sem a extensão .php
        // e sem o caminho completo 'Views/'. Ex: 'AddCliente/index' para Views/AddCliente/index.php
    }
}

// --- Exemplo de uma classe Controller base (se você não tiver uma) ---
// Normalmente estaria em Controllers/Controller.php ou Core/Controller.php
// Inclua este bloco APENAS se você ainda não tiver uma classe Controller definida em seu projeto.
if (!class_exists('Controller')) {
    class Controller {
        public function __construct() {
            // Construtor da classe base, pode ser vazio ou ter inicializações comuns.
        }

        // Método para carregar as views
        public function loadView($viewName, $viewData = []) {
            // Extrai os dados para que as variáveis fiquem disponíveis na view (mesmo que vazias por enquanto)
            extract($viewData); 
            
            // Inclui o arquivo da view
            // Ajuste este caminho se suas views não estiverem diretamente em 'Views/'
            $viewPath = __DIR__ . '/../Views/' . $viewName . '.php'; 
            
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                die("Erro: View '$viewName' não encontrada no caminho esperado: '$viewPath'.");
            }
        }
    }
}
// --- Fim do Exemplo de Controller base ---

?>