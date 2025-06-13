<?php
// File: TI-MANAGER/Core/Core.php

class Core {
    protected $session;

    public function run(){
        global $db;

        $this->session = new Session();

        $url = '/';
        if(isset($_GET['url'])){
            $url .= $_GET['url'];
        }

        $params = array();

        if(!empty($url) && $url != '/'){
            $urlSegments = explode('/', $url);
            array_shift($urlSegments);

            $currentController = ucfirst(array_shift($urlSegments));
            if (empty($currentController)) {
                $currentController = 'Login';
            }
            $currentController .= 'Controller';

            $currentAction = array_shift($urlSegments);
            if (empty($currentAction)) {
                $currentAction = 'index';
            }

            $params = $urlSegments;

        } else {
            $currentController = 'LoginController';
            $currentAction = 'adm';
        }

        switch (true) {
            case ($currentController === 'LoginController' && $currentAction === 'adm' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new LoginController();
                $controller->adm();
                break;
            case ($currentController === 'LoginController' && $currentAction === 'funcionario' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new LoginController();
                $controller->funcionario();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new AdmController();
                $controller->login();
                break;
            case ($currentController === 'FuncionarioController' && $currentAction === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new FuncionarioController();
                $controller->login();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'logout' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->logout();
                break;
            case ($currentController === 'FuncionarioController' && $currentAction === 'logout' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new FuncionarioController();
                $controller->logout();
                break;
            case ($currentController === 'HomeController' && $currentAction === 'index' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->home();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'funcionarios' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->funcionarios();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'empresas' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->empresas();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'historico' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new AdmController();
                $controller->historico();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'createFuncionarioTI' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new AdmController();
                $controller->createFuncionarioTI();
                break;
            case ($currentController === 'AdmController' && $currentAction === 'getFuncionarioTIJson' && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($params[0])):
                $controller = new AdmController();
                $controller->getFuncionarioTIJson((int)$params[0]);
                break;
            case ($currentController === 'AdmController' && $currentAction === 'updateFuncionarioTI' && $_SERVER['REQUEST_METHOD'] === 'POST'):
                $controller = new AdmController();
                $controller->updateFuncionarioTI();
                break;
            case ($currentController === 'ChamadosController' && $currentAction === 'index' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new FuncionarioController();
                $controller->chamados();
                break;
            case ($url === '/' && $_SERVER['REQUEST_METHOD'] === 'GET'):
                $controller = new LoginController();
                $controller->adm();
                break;
            default:
                if (class_exists($currentController) && method_exists($currentController, $currentAction)) {
                    $controller = new $currentController();
                    call_user_func_array(array($controller, $currentAction), $params);
                } else {
                    $controller = new NotFoundController();
                    $controller->index();
                }
                break;
        }
    }
}
