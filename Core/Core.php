<?php
// File: TI-MANAGER/Core/Core.php

class Core {
    protected $session; // Propriedade para armazenar a instância da Session

    public function run(){
        global $db; // Acessa a variável global $db, já definida em Support/Config.php

        $this->session = new Session(); // Instancia o gerenciador de sessão

        $url = '/'; // Inicializa $url. É esta variável que contém o caminho limpo da URL.
        if(isset($_GET['url'])){
            $url .= $_GET['url'];
        }

        $params = array();

        if(!empty($url) && $url != '/'){
            $urlSegments = explode('/', $url);
            array_shift($urlSegments); // Remove o primeiro '/' vazio ou o nome da pasta do projeto se houver

            // Determina o controlador (ex: 'Adm', 'Login')
            $currentController = ucfirst(array_shift($urlSegments)); // Pega o primeiro segmento e capitaliza
            if (empty($currentController)) { // Se não houver, assume 'Login'
                $currentController = 'Login';
            }
            $currentController .= 'Controller'; // Adiciona 'Controller'

            // Determina a ação/método
            $currentAction = array_shift($urlSegments); // Pega o segundo segmento
            if (empty($currentAction)) { // Se não houver, assume 'index'
                $currentAction = 'index';
            }

            // O que sobrar são os parâmetros
            $params = $urlSegments;

        } else {
            // Se a URL estiver vazia (raiz do site), vai para o login ADM por padrão
            $currentController = 'LoginController';
            $currentAction = 'adm';
            // Para a rota padrão, a $url já é '/'.
        }

        // --- Lógica de Roteamento EXATA (usando $url para matching direto) ---
        // Este switch agora verifica combinações de Controller/Action/Método HTTP
        // e URLs com parâmetros explícitos.
        // O fallback para call_user_func_array continua para rotas não listadas.

        // AQUI ESTÃO AS ROTAS FIXAS QUE TÊM PRIORIDADE
        switch (true) {
            // Rotas GET para exibir formulários de login
            case ($currentController === 'LoginController' && $currentAction === 'adm' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new LoginController();
                $controller->adm();
                break;
            case ($currentController === 'LoginController' && $currentAction === 'funcionario' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new LoginController();
                $controller->funcionario();
                break;

            // Rotas POST para processar login
            case ($currentController === 'AdmController' && $currentAction === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new AdmController();
                $controller->login();
                break;
            case ($currentController === 'FuncionarioController' && $currentAction === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new FuncionarioController();
                $controller->login();
                break;

            // Rotas de Logout (GET)
            case ($currentController === 'AdmController' && $currentAction === 'logout' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->logout();
                break;
            case ($currentController === 'FuncionarioController' && $currentAction === 'logout' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new FuncionarioController();
                $controller->logout();
                break;

            // Rota para a HOME do ADM (GET)
            case ($currentController === 'HomeController' && $currentAction === 'index' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController(); // AdmController::home() lida com isso
                $controller->home();
                break;

            // ROTA: Listagem de Funcionários para o ADM (GET)
            case ($currentController === 'AdmController' && $currentAction === 'funcionarios' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->funcionarios();
                break;

            // ROTA: Listagem de Empresas para o ADM (GET)
            case ($currentController === 'AdmController' && $currentAction === 'empresas' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->empresas();
                break;

            // ROTA: Histórico de Chamados para o ADM (GET)
            case ($currentController === 'AdmController' && $currentAction === 'historico' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->historico();
                break;

            // ROTA: Processar Cadastro de Funcionário TI (POST)
            case ($currentController === 'AdmController' && $currentAction === 'createFuncionarioTI' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new AdmController();
                $controller->createFuncionarioTI();
                break;

            // ROTA ADICIONADA: Obter Dados de Funcionário TI por ID (GET, AJAX)
            // Ex: index.php?url=Adm/getFuncionarioTIJson/123
            case ($currentController === 'AdmController' && $currentAction === 'getFuncionarioTIJson' && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($params[0])):
                $controller = new AdmController();
                $controller->getFuncionarioTIJson((int)$params[0]); // Passa o ID como parâmetro
                break;
            
            // ROTA ADICIONADA: Processar Edição de Funcionário TI (POST)
            case ($currentController === 'AdmController' && $currentAction === 'updateFuncionarioTI' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new AdmController();
                $controller->updateFuncionarioTI();
                break;

            // Rota para a página de Chamados dos Funcionários (GET)
            case ($currentController === 'ChamadosController' && $currentAction === 'index' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new FuncionarioController();
                $controller->chamados();
                break;

            // Rota padrão (raiz do projeto, GET) - CORRIGIDO: usando $url
            case ($url === '/' && $_SERVER['REQUEST_METHOD'] === 'GET'): // <<< CORRIGIDO AQUI
                $controller = new LoginController();
                $controller->adm();
                break;

            default:
                // Fallback para roteamento dinâmico (Controller/Action/Params)
                // Verifica se a classe do controlador e o método existem
                if (class_exists($currentController) && method_exists($currentController, $currentAction)) {
                    $controller = new $currentController();
                    call_user_func_array(array($controller, $currentAction), $params);
                } else {
                    // Se o controlador ou método não existirem
                    $controller = new NotFoundController();
                    $controller->index();
                }
                break;
        }
    }
}
