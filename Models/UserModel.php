<?php
// File: TI-MANAGER/Models/UserModel.php

class UserModel extends Model
{
    private $table_name = "users";          // Tabela principal de usuários
    private $employees_table = "employees"; // Tabela de funcionários

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
     *
     * @param string $email Email para login.
     * @param string $password Senha em texto puro.
     * @return array|null Dados do usuário se as credenciais forem válidas, caso contrário, null.
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
     *
     * @param string|null $roleFilter O papel do funcionário a ser filtrado (ex: 'funcionario_ti', 'funcionario_cliente').
     * @param int|null    $clientIdFilter O ID da empresa cliente para filtrar funcionários.
     * @return array Um array de arrays associativos com os dados dos funcionários.
     */
    public function getFuncionariosFiltered(?string $roleFilter = null, ?int $clientIdFilter = null): array
    {
        $query = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.cpf,
                    u.role,
                    u.created_at,
                    c.company_name
                  FROM " . $this->table_name . " u
                  LEFT JOIN " . $this->employees_table . " e ON u.id = e.user_id
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

    /**
     * Busca um usuário pelo email.
     * Usado para verificar a unicidade do email antes do cadastro.
     * @param string $email
     * @return array|null Dados do usuário se encontrado, caso contrário, null.
     */
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Busca um usuário pelo CPF.
     * Usado para verificar a unicidade do CPF antes do cadastro.
     * @param string $cpf CPF numérico.
     * @return array|null Dados do usuário se encontrado, caso contrário, null.
     */
    public function findByCpf(string $cpf): ?array
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE cpf = :cpf LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Cria um novo usuário Funcionário TI e o registra na tabela 'employees'.
     *
     * @param string $name Nome do funcionário.
     * @param string $cpf CPF do funcionário (apenas números).
     * @param string $email Email do funcionário.
     * @param string $hashedPassword Senha hashed.
     * @param string $role Papel do usuário (deve ser 'funcionario_ti').
     * @param string $funcao A função específica do funcionário. (Esta função não é armazenada na tabela users/employees, mas pode ser se adicionado campo)
     * @param int|null $clientId ID da empresa cliente para atribuir, ou null se não houver atribuição.
     * @return int|null O ID do novo usuário inserido, ou null em caso de falha.
     */
    public function createFuncionarioTI(string $name, string $cpf, string $email, string $hashedPassword, string $role, string $funcao, ?int $clientId): ?int
    {
        // Começa uma transação para garantir que ambas as inserções (users e employees) sejam atômicas
        $this->db->beginTransaction();
        try {
            // 1. Insere o usuário na tabela 'users'
            $queryUser = "INSERT INTO " . $this->table_name . " (name, email, password, role, cpf)
                          VALUES (:name, :email, :password, :role, :cpf)";
            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->bindParam(':name', $name);
            $stmtUser->bindParam(':email', $email);
            $stmtUser->bindParam(':password', $hashedPassword);
            $stmtUser->bindParam(':role', $role);
            $stmtUser->bindParam(':cpf', $cpf);
            $stmtUser->execute();

            $newUserId = $this->db->lastInsertId(); // Obtém o ID do usuário recém-criado

            if ($newUserId) {
                // 2. Insere o registro na tabela 'employees'
                $queryEmployee = "INSERT INTO " . $this->employees_table . " (user_id, client_id)
                                  VALUES (:user_id, :client_id)";
                $stmtEmployee = $this->db->prepare($queryEmployee);
                $stmtEmployee->bindParam(':user_id', $newUserId, PDO::PARAM_INT);
                // O client_id pode ser NULL, então bindValue com PDO::PARAM_NULL se for o caso
                if ($clientId !== null) {
                    $stmtEmployee->bindParam(':client_id', $clientId, PDO::PARAM_INT);
                } else {
                    $stmtEmployee->bindValue(':client_id', null, PDO::PARAM_NULL);
                }
                $stmtEmployee->execute();

                $this->db->commit(); // Confirma a transação
                return (int)$newUserId;
            }

            $this->db->rollBack(); // Em caso de falha na inserção de usuário
            return null;

        } catch (PDOException $e) {
            $this->db->rollBack(); // Desfaz a transação em caso de qualquer erro
            error_log("Erro ao criar funcionário TI: " . $e->getMessage());
            return null;
        }
    }
}
