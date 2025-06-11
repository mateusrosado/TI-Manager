<?php

session_start();

spl_autoload_register(function($class){
    if(file_exists('Controllers/'.$class.'.php')){
        require 'Controllers/'.$class.'.php';
    } else if(file_exists('Models/'.$class.'.php')){
        require 'Models/'.$class.'.php';
    } else if(file_exists('Core/'.$class.'.php')){
        require 'Core/'.$class.'.php';
    }else if(file_exists('Support/'.$class.'.php')){
        require 'Support/'.$class.'.php';
    }
});

// Inclui arquivos de suporte que não são classes (ou classes que não seguem o padrão de autoload)
require 'Support/Config.php';   // Contém definições de constantes e a conexão global $db
require 'Support/Email.php';    // Se for uma classe, pode ser carregada pelo autoloader
require 'Support/Helpers.php';  // Funções utilitárias
require 'Support/Message.php';  // Gerenciamento de mensagens

// Agora, inclua os novos modelos explicitamente (ou ajuste seu autoloader se ele for mais inteligente)
// Se o autoloader já pega de Models/, esses requires são redundantes, mas garantem o carregamento.
require_once 'Models/ClienteModel.php';
require_once 'Models/TicketModel.php';


$core = new Core();
$core->run();
