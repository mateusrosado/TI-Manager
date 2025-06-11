<?php
// File: TI-MANAGER/Models/UserModel.php

class UserModel extends Model
{
    private $table_name = "users";

    public function __construct()
    {
        parent::__construct(); // Chama o construtor da classe base 'Model' para inicializar $this->db
    }

    /**
     * Encontra um usuário pelo CNPJ de login e verifica a senha.
     * Usado para roles 'admin' e 'adm_cliente'.
     *
     * @param string $cnpj CNPJ para login.
     * @param string $password Senha em texto puro.
     * @return array|null Dados do usuário se as credenciais forem válidas, caso contrário, null.
     */
    public function findByCnpjAndVerifyPassword(string $cnpj, string $password): ?array
    {
        // Garante que o CNPJ esteja formatado corretamente para a busca (apenas números)
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE cnpj_login = :cnpj_login AND (role = 'admin' OR role = 'adm_cliente') LIMIT 0,1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cnpj_login', $cnpj);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Remove a senha do array antes de retornar para segurança
            return $user;
        }

        return null;
    }

    /**
     * Encontra um usuário pelo email e verifica a senha.
     * Usado para roles 'funcionario_ti' e 'funcionario_cliente'.
     *
     * @param string $email
     * @param string $password
     * @return array|null Dados do usuário se as credenciais forem válidas, caso contrário, null.
     */
    public function findByEmailAndVerifyPassword(string $email, string $password): ?array
    {
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE email = :email AND (role = 'funcionario_ti' OR role = 'funcionario_cliente') LIMIT 0,1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Remove a senha do array antes de retornar para segurança
            return $user;
        }

        return null;
    }

    /**
     * Obtém uma lista de todos os funcionários de TI e funcionários clientes,
     * incluindo a empresa à qual estão atribuídos (se aplicável).
     * @return array Um array de arrays associativos com os dados dos funcionários.
     */
    public function getFuncionariosWithCompanyInfo(): array
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
                  WHERE u.role IN ('funcionario_ti', 'funcionario_cliente')
                  ORDER BY u.name ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
