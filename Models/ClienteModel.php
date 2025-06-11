<?php
// File: TI-MANAGER/Models/ClienteModel.php

class ClienteModel extends Model
{
    private $table_name = "clients";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtém todos os clientes cadastrados.
     * @return array
     */
    public function getAllClients(): array
    {
        $query = "SELECT id, company_name, cnpj, contact, address, data_abertura, data_encerramento
                  FROM {$this->table_name}
                  ORDER BY company_name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtém os dados de uma empresa cliente com base no user_id de um AdmCliente.
     * @param int $adminUserId
     * @return array|null
     */
    public function getClientByAdminUserId(int $adminUserId): ?array
    {
        $query = "SELECT id, company_name, cnpj, contact, address, data_abertura, data_encerramento
                  FROM {$this->table_name}
                  WHERE user_id = :admin_user_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':admin_user_id', $adminUserId, PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        return $client ?: null;
    }
}
