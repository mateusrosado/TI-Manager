<?php
// File: TI-MANAGER/Core/Controller.php

Class Controller{
    public function __construct() {
        // Construtor vazio para permitir que as classes filhas chamem parent::__construct().
    }

    public function loadView($viewName, $viewData = array() ){
        extract($viewData);
        require 'Views/'.$viewName.'.php'; // Caminho da view
    }

    public function loadTemplateSite($viewName, $viewData = array()){
        require 'Views/Templates/'.TEMPLATE.'.php';
    }
    public function loadTemplateAdmin($viewName, $viewData = array()){
        require 'Views/Templates/'.TEMPLATE_ADMIN.'.php';
    }

    public function loadViewInTemplate($viewName, $viewData = array()){
        extract($viewData);
        require 'Views/'.$viewName.'.php';
    }

    public function loadLibrary($lib){
        if(file_exists('Vendor/'.$lib.'.php')){
            require_once 'Vendor/'.$lib.'.php';
        }
    }

    /**
     * Redireciona o usuário para a página principal apropriada ('Home' ou 'Chamados')
     * com base no seu papel.
     * A URL é construída usando BASE_URL e o formato index.php?url=Controller/method.
     * @param string $role O papel do usuário.
     */
    protected function redirectToView(string $role)
    {
        switch ($role) {
            case 'admin':
            case 'adm_cliente':
                $this->loadView('Adm/home');
                break;
            case 'funcionario_ti':
            case 'funcionario_cliente':
                $this->loadView('Funcionario/chamados');
                break;
            default:
                $this->loadView('index');
                break;
        }
        exit();
    }
}
