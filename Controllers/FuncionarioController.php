<?php

class FuncionarioController extends Controller {

    public function indexTi() {
        $this->loadView('Funcionario/indexTi'); 
    }

    public function indexCliente() {
        $this->loadView('Funcionario/indexCliente'); 
    }
}
?>