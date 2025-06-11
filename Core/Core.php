<?php
// File: TI-MANAGER/Core/Core.php

class Core {
    protected $session; // Propriedade para armazenar a instância da Session

    public function run(){
        global $db; // Acessa a variável global $db, já definida em Support/Config.php

        $this->session = new Session(); // Instancia o gerenciador de sessão

        $url = '/';
        if(isset($_GET['url'])){
            $url .= $_GET['url'];
        }

        $params = array();

        if(!empty($url) && $url != '/'){
            $url = explode('/', $url);
            array_shift($url); // Remove o primeiro '/' vazio ou o nome da pasta do projeto se houver

            // Determina o controlador (ex: 'Adm', 'Login')
            if(isset($url[0]) && !empty($url[0])) {
                $currentController = ucfirst($url[0]).'Controller';
                array_shift($url);
            } else {
                $currentController = 'LoginController'; // Controlador padrão
            }

            // Determina a ação/método (ex: 'login', 'funcionarios')
            if(isset($url[0]) && !empty($url[0])){
                $currentAction = $url[0];
                array_shift($url);
            } else {
                $currentAction = 'index'; // Ação padrão
            }

            // O que sobrar são os parâmetros
            if(count($url) > 0){
                $params = $url;
            }

        } else {
            // Se a URL estiver vazia (raiz do site), vai para o login ADM por padrão
            $currentController = 'LoginController';
            $currentAction = 'adm';
        }

        // --- Lógica de Roteamento EXATA (usando $url para matching direto) ---
        switch ($url) {
            // Rotas GET para exibir formulários de login
            case 'login/adm':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $controller = new LoginController();
                    $controller->adm();
                } else {
                    http_response_code(405); echo "Método não permitido para esta rota.";
                }
                break;
            case 'login/funcionario':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $controller = new LoginController();
                    $controller->funcionario();
                } else {
                    http_response_code(405); echo "Método não permitido para esta rota.";
                }
                break;

            // Rotas POST para processar login
            case 'Adm/login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller = new AdmController();
                    $controller->login();
                } else {
                    http_response_code(405); echo "Método não permitido para esta rota.";
                }
                break;
            case 'Funcionario/login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller = new FuncionarioController();
                    $controller->login();
                } else {
                    http_response_code(405); echo "Método não permitido para esta rota.";
                }
                break;

            // Rotas de Logout
            case 'Adm/logout':
                $controller = new AdmController();
                $controller->logout();
                break;
            case 'Funcionario/logout':
                $controller = new FuncionarioController();
                $controller->logout();
                break;

            // Rota para a HOME do ADM
            case 'Home/index':
                $controller = new AdmController();
                $controller->home();
                break;

            // ROTA ADICIONADA: Listagem de Funcionários para o ADM
            case 'Adm/funcionarios':
                $controller = new AdmController();
                $controller->funcionarios();
                break;

            // ROTA ADICIONADA: Listagem de Empresas para o ADM
            case 'Adm/empresas': // NOVO
                $controller = new AdmController();
                $controller->empresas(); // Chama o método empresas()
                break;

            // ROTA ADICIONADA: Histórico de Chamados para o ADM
            case 'Adm/historico': // NOVO
                $controller = new AdmController();
                $controller->historico(); // Chama o método historico()
                break;

            // Rota para a página de Chamados dos Funcionários (chamará FuncionarioController::chamados())
            case 'Chamados/index':
                $controller = new FuncionarioController();
                $controller->chamados();
                break;

            // Rota padrão (raiz do projeto)
            case '':
                $controller = new LoginController();
                $controller->adm();
                break;

            default:
                // Fallback para roteamento dinâmico (Controller/Action/Params)
                // Usar $currentController e $currentAction que foram parseados do URL
                if (method_exists($currentController, $currentAction)) {
                    $controller = new $currentController(); // Re-instancia para garantir o tipo correto
                    call_user_func_array(array($controller, $currentAction), $params);
                } else {
                    $controller = new NotFoundController();
                    $controller->index();
                }
                break;
        }
    }
}
