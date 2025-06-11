<?php
// File: TI-MANAGER/index.php

// Inicia a sessão no topo, antes de qualquer saída.
session_start();

// Define a BASE_URL (AJUSTE ISSO CONFORME A SUA CONFIGURAÇÃO DE SERVIDOR)
// Exemplo: Se o projeto está em http://localhost/TI-MANAGER/, BASE_URL deve ser '/TI-MANAGER/'
// Se o projeto está diretamente na raiz do servidor (http://localhost/), BASE_URL deve ser '/'
// Esta URL é usada para construir todos os links e ações de formulário.

// Registra o autoloader para carregar classes automaticamente
// Ele procurará as classes nos diretórios Controllers, Models, Core e Support
spl_autoload_register(function($class){
    if(file_exists('Controllers/'.$class.'.php')){
        require 'Controllers/'.$class.'.php';
    } else if(file_exists('Models/'.$class.'.php')){
        require 'Models/'.$class.'.php';
    } else if(file_exists('Core/'.$class.'.php')){
        require 'Core/'.$class.'.php';
    }else if(file_exists('Support/'.$class.'.php')){ // Adiciona o diretório Support
        require 'Support/'.$class.'.php';
    }
});

// Inclui arquivos de suporte que não são classes (ou classes que não seguem o padrão de autoload)
require 'Support/Config.php';   // Contém definições de constantes e a conexão global $db
require 'Support/Email.php';    // Se for uma classe, pode ser carregada pelo autoloader
require 'Support/Helpers.php';  // Funções utilitárias
require 'Support/Message.php';  // Gerenciamento de mensagens

// Cria uma instância da classe Core e executa o roteador
$core = new Core();
$core->run();
