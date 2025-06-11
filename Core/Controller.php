<?php
// File: TI-MANAGER/Core/Controller.php

class Controller{
    // Construtor vazio para permitir que as classes filhas chamem parent::__construct()
    // sem erro, já que a classe base não tem um construtor com lógica específica.
    public function __construct() {}

    /**
     * Carrega uma view, extraindo os dados para variáveis locais.
     * @param string $viewName O nome do arquivo da view (ex: 'Login/adm').
     * @param array $viewData Um array associativo de dados a serem passados para a view.
     */
    public function loadView($viewName, $viewData = array() ){
        extract($viewData); // Extrai o array $viewData em variáveis separadas
        // O caminho completo da view é construído com base na raiz da aplicação.
        require 'Views/'.$viewName.'.php';
    }

    /**
     * Carrega um template de site. Assume que TEMPLATE está definido.
     * @param string $viewName O nome da view a ser carregada dentro do template.
     * @param array $viewData Dados para a view.
     */
    public function loadTemplateSite($viewName, $viewData = array()){
        // assume TEMPLATE é uma constante definida (ex: 'default')
        require 'Views/Templates/'.TEMPLATE.'.php';
    }

    /**
     * Carrega um template de administração. Assume que TEMPLATE_ADMIN está definido.
     * @param string $viewName O nome da view a ser carregada dentro do template.
     * @param array $viewData Dados para a view.
     */
    public function loadTemplateAdmin($viewName, $viewData = array()){
        // assume TEMPLATE_ADMIN é uma constante definida (ex: 'admin')
        require 'Views/Templates/'.TEMPLATE_ADMIN.'.php';
    }

    /**
     * Carrega uma view dentro de um template (uso interno em templates).
     * @param string $viewName O nome da view a ser carregada.
     * @param array $viewData Dados para a view.
     */
    public function loadViewInTemplate($viewName, $viewData = array()){
        extract($viewData);
        require 'Views/'.$viewName.'.php';
    }

    /**
     * Carrega uma biblioteca de terceiros do diretório Vendor.
     * @param string $lib O nome da biblioteca (ex: 'PHPMailer').
     */
    public function loadLibrary($lib){
        if(file_exists('Vendor/'.$lib.'.php')){
            require_once 'Vendor/'.$lib.'.php';
        }
    }

    /**
     * Redireciona o usuário para o dashboard apropriado com base no papel.
     * A URL é construída usando BASE_URL e o formato index.php?url=Controller/method.
     * @param string $role O papel do usuário ('admin', 'adm_cliente', 'funcionario_ti', 'funcionario_cliente').
     */
    protected function redirectToView(string $role)
    {
        switch ($role) {
            case 'admin':
                $this->loadView('Adm/home');
                break;
            case 'adm_cliente':
                $this->loadView('Adm/home');
                break;
            case 'funcionario_ti':
                $this->loadView('Funcionario/chamados-menu');
                break;
            case 'funcionario_cliente':
                $this->loadView('Funcionario/chamados-menu');
                break;
            default:
                $this->loadView('Login/index');
                break;
        }
        exit();
    }
}
