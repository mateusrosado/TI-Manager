<?php
// File: TI-MANAGER/Models/UserModel.php

class UserModel extends Model
{
    private $table_name = "users";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Encontra um usuário pelo CNPJ de login e verifica a senha.
     * Usado para roles 'admin' e 'adm_cliente'.
     */
    public function findByCnpjAndVerifyPassword(string $cnpj, string $password): ?array
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE cnpj_login = :cnpj_login AND (role = 'admin' OR role = 'adm_cliente') LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cnpj_login', $cnpj);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return null;
    }

    /**
     * Encontra um usuário pelo email e verifica a senha.
     * Usado para roles 'funcionario_ti' e 'funcionario_cliente'.
     */
    public function findByEmailAndVerifyPassword(string $email, string $password): ?array
    {
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE email = :email AND (role = 'funcionario_ti' OR role = 'funcionario_cliente') LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return null;
    }

    /**
     * Obtém uma lista de funcionários, filtrando por papel e, opcionalmente, por ID do cliente.
     */
    public function getFuncionariosFiltered(?string $roleFilter = null, ?int $clientIdFilter = null): array
    {
        $query = "SELECT
                    u.id,
                    u.name,
                    u.role,
                    u.created_at,
                    c.company_name
                  FROM users u
                  LEFT JOIN employees e ON u.id = e.user_id
                  LEFT JOIN clients c ON e.client_id = c.id
                  WHERE (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente')";

        $params = [];

        if ($roleFilter) {
            $query .= " AND u.role = :role_filter";
            $params[':role_filter'] = $roleFilter;
        }

        if ($clientIdFilter && $roleFilter === 'funcionario_cliente') {
            $query .= " AND e.client_id = :client_id_filter";
            $params[':client_id_filter'] = $clientIdFilter;
        }

        $query .= " ORDER BY u.name ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
