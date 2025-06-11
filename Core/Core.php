<?php
// File: TI-MANAGER/Core/Core.php

class Core {
    // Não precisamos de propriedades $db ou $session aqui, pois $db é global
    // e Session é instanciada conforme a necessidade nos controllers.

    public function run(){
        // A conexão com o banco de dados ($db) já é estabelecida e globalmente disponível
        // através do 'Support/Config.php', então não precisamos inicializá-la aqui.
        global $db; // Apenas acessa a variável global $db, não a cria novamente.

        $url = '/';
        if(isset($_GET['url'])){
            $url .= $_GET['url'];
        }

        $params = array(); // Para armazenar os parâmetros da URL

        if(!empty($url) && $url != '/'){
            $url = explode('/', $url);
            array_shift($url); // Remove o primeiro '/' vazio ou o nome da pasta do projeto se houver

            // Determina o nome do controlador a partir do primeiro segmento da URL
            if(isset($url[0]) && !empty($url[0])) {
                $currentController = ucfirst($url[0]).'Controller'; // Ex: 'login' -> 'LoginController'
                array_shift($url); // Remove o nome do controlador dos segmentos restantes
            } else {
                $currentController = 'LoginController'; // Controlador padrão se a URL não tiver um segmento de controlador
            }

            // Determina o nome da ação/método a partir do próximo segmento da URL
            if(isset($url[0]) && !empty($url[0])){
                $currentAction = $url[0];
                array_shift($url); // Remove o nome da ação/método dos segmentos restantes
            } else {
                $currentAction = 'index'; // Ação padrão se não houver um método especificado
            }

            // Qualquer segmento restante na URL são considerados parâmetros
            if(count($url) > 0){
                $params = $url;
            }

        } else {
            // Caso a URL seja apenas '/' (raiz do projeto), roteia para o LoginController::adm() como padrão
            $currentController = 'LoginController';
            $currentAction = 'adm'; // Ação padrão para a página inicial (login ADM)
        }

        // Verifica se o arquivo do controlador existe e se o método existe na classe
        // Se não existir, roteia para o NotFoundController
        if(!file_exists('Controllers/'.$currentController.'.php') || !method_exists($currentController, $currentAction)){
            $currentController = 'NotFoundController';
            $currentAction = 'index'; // Método padrão para NotFoundController (geralmente exibe um erro 404)
        }

        // Cria uma instância do controlador determinado
        // O spl_autoload_register em index.php cuidará de incluir o arquivo do controlador.
        $controller = new $currentController();

        // Chama o método (ação) do controlador, passando os parâmetros da URL
        call_user_func_array(array($controller, $currentAction), $params);
    }
}
