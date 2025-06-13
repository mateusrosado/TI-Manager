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
                COUNT(e.user_id) AS funcionarios_count,
                (
                    SELECT COUNT(*) FROM tickets t
                    WHERE t.client_id = c.id
                      AND t.status NOT IN ('Recusado', 'Encerrado')
                ) AS chamados_ativos
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
        $this->db->beginTransaction();
        try {
            // 1. Buscar o user_id do adm_cliente da empresa
            $queryAdm = "SELECT user_id FROM {$this->table_name} WHERE id = :empresa_id";
            $stmtAdm = $this->db->prepare($queryAdm);
            $stmtAdm->bindParam(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmtAdm->execute();
            $adm = $stmtAdm->fetch(PDO::FETCH_ASSOC);

            // 2. Buscar todos os user_id dos funcionários cliente dessa empresa
            $queryFuncionarios = "SELECT user_id FROM employees WHERE client_id = :empresa_id";
            $stmtFunc = $this->db->prepare($queryFuncionarios);
            $stmtFunc->bindParam(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmtFunc->execute();
            $funcionarios = $stmtFunc->fetchAll(PDO::FETCH_COLUMN);

            // 3. Excluir todos os funcionários cliente (tabela users)
            if (!empty($funcionarios)) {
                $in = implode(',', array_fill(0, count($funcionarios), '?'));
                $queryDelFuncs = "DELETE FROM users WHERE id IN ($in) AND role = 'funcionario_cliente'";
                $stmtDelFuncs = $this->db->prepare($queryDelFuncs);
                foreach ($funcionarios as $k => $id) {
                    $stmtDelFuncs->bindValue($k + 1, $id, PDO::PARAM_INT);
                }
                $stmtDelFuncs->execute();
            }

            // 4. Excluir o adm_cliente (tabela users)
            if ($adm && $adm['user_id']) {
                $queryDelAdm = "DELETE FROM users WHERE id = :adm_id AND role = 'adm_cliente'";
                $stmtDelAdm = $this->db->prepare($queryDelAdm);
                $stmtDelAdm->bindParam(':adm_id', $adm['user_id'], PDO::PARAM_INT);
                $stmtDelAdm->execute();
            }

            // 5. Excluir a empresa (tabela clients)
            $queryDelEmpresa = "DELETE FROM {$this->table_name} WHERE id = :empresa_id";
            $stmtDelEmpresa = $this->db->prepare($queryDelEmpresa);
            $stmtDelEmpresa->bindParam(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmtDelEmpresa->execute();

            // Após excluir usuários:
            $queryDelEmployees = "DELETE FROM employees WHERE client_id = :empresa_id";
            $stmtDelEmployees = $this->db->prepare($queryDelEmployees);
            $stmtDelEmployees->bindParam(':empresa_id', $empresaId, PDO::PARAM_INT);
            $stmtDelEmployees->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao excluir empresa e usuários relacionados: " . $e->getMessage());
            return false;
        }
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
