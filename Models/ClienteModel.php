<?php
// File: TI-MANAGER/Models/ClientModel.php

class ClienteModel extends Model
{
    private $table_name = "clients";

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllClients(): array
    {
        $query = "SELECT 
                c.id, 
                c.company_name, 
                c.cnpj, 
                c.contact, 
                c.address, 
                c.data_abertura, 
                c.data_encerramento,
                COUNT(e.user_id) AS funcionarios_count
              FROM " . $this->table_name . " c
              LEFT JOIN employees e ON e.client_id = c.id
              GROUP BY c.id, c.company_name, c.cnpj, c.contact, c.address, c.data_abertura, c.data_encerramento
              ORDER BY c.company_name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientByAdminUserId(int $adminUserId): ?array
    {
        $query = "SELECT id, company_name, cnpj, contact, address, data_abertura, data_encerramento
                  FROM " . $this->table_name . "
                  WHERE user_id = :admin_user_id LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':admin_user_id', $adminUserId, PDO::PARAM_INT);
        $stmt->execute();

        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }

    public function findByCnpj(string $cnpj): ?array
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE cnpj = :cnpj LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createClient(string $companyName, string $cnpj, string $contact, string $address, int $adminUserId): ?int
    {
        $query = "INSERT INTO " . $this->table_name . " (user_id, company_name, cnpj, contact, address, data_abertura)
                  VALUES (:user_id, :company_name, :cnpj, :contact, :address, NOW())";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $adminUserId, PDO::PARAM_INT);
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);

        try {
            $stmt->execute();
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar cliente: " . $e->getMessage());
            return null;
        }
    }

    public function excluirEmpresa(int $empresaId): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $empresaId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEmpresaById(int $id): ?array
    {
        $query = "SELECT id, company_name, cnpj, contact, address FROM {$this->table_name} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
        return $empresa ?: null;
    }

    public function updateEmpresa(int $id, string $companyName, string $cnpj, string $contact, string $address): bool
    {
        $query = "UPDATE {$this->table_name}
                SET company_name = :company_name, cnpj = :cnpj, contact = :contact, address = :address
                WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
