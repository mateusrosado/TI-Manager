<?php
// File: TI-MANAGER/Models/ClientModel.php

class ClienteModel extends Model
{
    private $table_name = "clients";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtém todos os clientes cadastrados.
     * @return array Um array de arrays associativos com os dados dos clientes.
     */
    public function getAllClients(): array
    {
        $query = "SELECT id, company_name, cnpj, contact, address, data_abertura, data_encerramento
                  FROM " . $this->table_name . "
                  ORDER BY company_name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
