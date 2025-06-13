<?php
// File: TI-MANAGER/Models/UserModel.php

class UserModel extends Model
{
    private $table_name = "users";          // Tabela principal de usuários
    private $employees_table = "employees"; // Tabela de funcionários
    private $tickets_table = "tickets";     // Tabela de tickets (para verificações de inativação)

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
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE cnpj_login = :cnpj_login AND (role = 'admin' OR role = 'adm_cliente') AND is_active = TRUE LIMIT 1"; // Adicionado is_active = TRUE
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
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE email = :email AND (role = 'funcionario_ti' OR role = 'funcionario_cliente') AND is_active = TRUE LIMIT 1"; // Adicionado is_active = TRUE
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
     * Retorna apenas funcionários ATIVOS por padrão.
     *
     * @param string|null $roleFilter O papel do funcionário a ser filtrado (ex: 'funcionario_ti', 'funcionario_cliente').
     * @param int|null    $clientIdFilter O ID da empresa cliente para filtrar funcionários.
     * @param bool        $onlyActive Se TRUE, retorna apenas funcionários ativos.
     * @return array Um array de arrays associativos com os dados dos funcionários.
     */
    public function getFuncionariosFiltered(?string $roleFilter = null, ?int $clientIdFilter = null, bool $onlyActive = true): array
    {
        $query = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.cpf,
                    u.role,
                    u.created_at,
                    u.is_active, -- Inclui a coluna is_active
                    c.company_name,
                    e.client_id,
                    e.funcao -- INCLUÍDO: Agora seleciona a função da tabela employees
                  FROM " . $this->table_name . " u
                  LEFT JOIN " . $this->employees_table . " e ON u.id = e.user_id
                  LEFT JOIN clients c ON e.client_id = c.id
                  WHERE (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente')";

        $params = [];

        if ($onlyActive) {
            $query .= " AND u.is_active = TRUE"; // Filtra por ativos
        }

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
     * @param string $funcao A função específica do funcionário.
     * @param int|null $clientId ID da empresa cliente para atribuir, ou null se não houver atribuição.
     * @return int|null O ID do novo usuário inserido, ou null em caso de falha.
     */
    public function createFuncionarioTI(string $name, string $cpf, string $email, string $hashedPassword, string $role, string $funcao, ?int $clientId): ?int
    {
        $this->db->beginTransaction();
        try {
            $queryUser = "INSERT INTO " . $this->table_name . " (name, email, password, role, cpf, is_active)
                          VALUES (:name, :email, :password, :role, :cpf, TRUE)"; // is_active = TRUE por padrão
            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->bindParam(':name', $name);
            $stmtUser->bindParam(':email', $email);
            $stmtUser->bindParam(':password', $hashedPassword);
            $stmtUser->bindParam(':role', $role);
            $stmtUser->bindParam(':cpf', $cpf);
            $stmtUser->execute();

            $newUserId = $this->db->lastInsertId();

            if ($newUserId) {
                // 2. Insere o registro na tabela 'employees', incluindo a função
                $queryEmployee = "INSERT INTO " . $this->employees_table . " (user_id, client_id, funcao)
                                  VALUES (:user_id, :client_id, :funcao)";
                $stmtEmployee = $this->db->prepare($queryEmployee);
                $stmtEmployee->bindParam(':user_id', $newUserId, PDO::PARAM_INT);
                if ($clientId !== null) {
                    $stmtEmployee->bindParam(':client_id', $clientId, PDO::PARAM_INT);
                } else {
                    $stmtEmployee->bindValue(':client_id', null, PDO::PARAM_NULL);
                }
                $stmtEmployee->bindParam(':funcao', $funcao); // INCLUÍDO: Bind da função
                $stmtEmployee->execute();

                $this->db->commit();
                return (int)$newUserId;
            }

            $this->db->rollBack();
            return null;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao criar funcionário TI: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cria um novo usuário com o papel de 'adm_cliente'.
     *
     * @param string $companyName Nome da empresa (usado como nome do usuário).
     * @param string $cnpj CNPJ da empresa.
     * @param string $hashedPassword Senha do usuário (já deve estar criptografada).
     * @return int|null O ID do novo usuário criado, ou null em caso de erro.
     */
    public function createAdmClienteUser(string $companyName, string $cnpj, string $hashedPassword): ?int
    {
        $query = "INSERT INTO {$this->table_name} 
        (name, email, password, role, cnpj_login, cpf, created_at, is_active)
        VALUES (:name, :email, :password, 'adm_cliente', :cnpj_login, :cpf, NOW(), 1)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $companyName);
        $stmt->bindValue(':email', null, PDO::PARAM_NULL);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':cnpj_login', $cnpj);
        $stmt->bindValue(':cpf', null, PDO::PARAM_NULL);
        try {
            $stmt->execute();
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar adm_cliente: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtém os detalhes completos de um funcionário (usuário) por ID.
     * Inclui dados da tabela users e employees (client_id, funcao).
     * @param int $id O ID do usuário/funcionário.
     * @return array|null Os dados do funcionário, ou null se não encontrado.
     */
    public function getFuncionarioById(int $id): ?array
    {
        $query = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.cpf,
                    u.role,
                    u.is_active,
                    e.client_id,
                    e.funcao -- INCLUÍDO: Agora seleciona a função da tabela employees
                  FROM " . $this->table_name . " u
                  LEFT JOIN " . $this->employees_table . " e ON u.id = e.user_id
                  LEFT JOIN clients c ON e.client_id = c.id
                  WHERE u.id = :id AND (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente') LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Atualiza as informações de um funcionário TI.
     *
     * @param int $id O ID do funcionário a ser atualizado.
     * @param string $name Nome do funcionário.
     * @param string $cpf CPF do funcionário (apenas números).
     * @param string $email Email do funcionário.
     * @param string|null $hashedPassword Nova senha hashed, ou null para não alterar.
     * @param string $funcao A função específica do funcionário.
     * @param int|null $clientId ID da empresa cliente para atribuir, ou null para remover atribuição.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function updateFuncionarioTI(int $id, string $name, string $cpf, string $email, ?string $hashedPassword, string $funcao, ?int $clientId): bool
    {
        $this->db->beginTransaction();
        try {
            // 1. Atualiza a tabela 'users'
            $queryUser = "UPDATE " . $this->table_name . " SET
                          name = :name,
                          email = :email,
                          cpf = :cpf";
            if ($hashedPassword !== null) {
                $queryUser .= ", password = :password";
            }
            $queryUser .= " WHERE id = :id";

            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->bindParam(':name', $name);
            $stmtUser->bindParam(':email', $email);
            $stmtUser->bindParam(':cpf', $cpf);
            $stmtUser->bindParam(':id', $id, PDO::PARAM_INT);
            if ($hashedPassword !== null) {
                $stmtUser->bindParam(':password', $hashedPassword);
            }
            $stmtUser->execute();

            // 2. Atualiza ou insere/remove na tabela 'employees'
            // Verifica se já existe um registro em 'employees' para este user_id
            $queryCheckEmployee = "SELECT user_id FROM " . $this->employees_table . " WHERE user_id = :user_id LIMIT 1";
            $stmtCheckEmployee = $this->db->prepare($queryCheckEmployee);
            $stmtCheckEmployee->bindParam(':user_id', $id, PDO::PARAM_INT);
            $stmtCheckEmployee->execute();
            $employeeExists = $stmtCheckEmployee->fetch(PDO::FETCH_ASSOC);

            if ($employeeExists) {
                // Se já existe, atualiza
                $queryEmployee = "UPDATE " . $this->employees_table . " SET client_id = :client_id, funcao = :funcao WHERE user_id = :user_id"; // INCLUÍDO: Atualiza a função
                $stmtEmployee = $this->db->prepare($queryEmployee);
                $stmtEmployee->bindParam(':user_id', $id, PDO::PARAM_INT);
                if ($clientId !== null) {
                    $stmtEmployee->bindParam(':client_id', $clientId, PDO::PARAM_INT);
                } else {
                    $stmtEmployee->bindValue(':client_id', null, PDO::PARAM_NULL);
                }
                $stmtEmployee->bindParam(':funcao', $funcao); // INCLUÍDO: Bind da função
                $stmtEmployee->execute();
            } else {
                // Se não existe (e o usuário é funcionario_ti/cliente), insere.
                // Isso cobre o caso de um usuário ser criado com role funcionario_ti/cliente
                // e não ter tido um registro em 'employees' ainda.
                $queryInsertEmployee = "INSERT INTO " . $this->employees_table . " (user_id, client_id, funcao) VALUES (:user_id, :client_id, :funcao)";
                $stmtInsertEmployee = $this->db->prepare($queryInsertEmployee);
                $stmtInsertEmployee->bindParam(':user_id', $id, PDO::PARAM_INT);
                 if ($clientId !== null) {
                    $stmtInsertEmployee->bindParam(':client_id', $clientId, PDO::PARAM_INT);
                } else {
                    $stmtInsertEmployee->bindValue(':client_id', null, PDO::PARAM_NULL);
                }
                $stmtInsertEmployee->bindParam(':funcao', $funcao);
                $stmtInsertEmployee->execute();
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao atualizar funcionário TI: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Inativa um funcionário após verificar se ele pode ser inativado.
     * Um funcionário só pode ser inativado se não tiver chamados 'Aberto', 'Pendente' ou 'Em andamento'
     * e não estiver atribuído a uma empresa no momento (client_id IS NULL ou 0).
     *
     * @param int $funcionarioId O ID do funcionário a ser inativado.
     * @return array Retorna um array com 'success' (bool) e 'message' (string).
     */
    public function inativarFuncionario(int $funcionarioId): array
    {
        $this->db->beginTransaction();
        try {
            // 1. Verificar se o funcionário existe e é um funcionário TI/Cliente
            $queryFuncionario = "SELECT u.id, u.role, e.client_id
                                 FROM " . $this->table_name . " u
                                 LEFT JOIN " . $this->employees_table . " e ON u.id = e.user_id
                                 WHERE u.id = :funcionario_id AND (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente') LIMIT 1";
            $stmtFuncionario = $this->db->prepare($queryFuncionario);
            $stmtFuncionario->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtFuncionario->execute();
            $funcionarioInfo = $stmtFuncionario->fetch(PDO::FETCH_ASSOC);

            if (!$funcionarioInfo) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Funcionário não encontrado ou não é um funcionário TI/Cliente válido.'];
            }

            // 2. Verificar se o funcionário está atribuído a uma empresa
            // (client_id NULL ou 0 significa não atribuído)
            if ($funcionarioInfo['client_id'] !== null && $funcionarioInfo['client_id'] !== 0) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Não é possível inativar o funcionário. Ele está atribuído a uma empresa.'];
            }

            // 3. Verificar se o funcionário tem chamados em andamento (Aberto, Pendente, Em andamento)
            $queryChamadosAbertos = "SELECT COUNT(t.id) AS open_tickets
                                     FROM " . $this->tickets_table . " t
                                     LEFT JOIN " . $this->employees_table . " e ON t.assigned_to = e.id
                                     WHERE e.user_id = :funcionario_id AND t.status IN ('Aberto', 'Pendente', 'Em andamento')";
            $stmtChamadosAbertos = $this->db->prepare($queryChamadosAbertos);
            $stmtChamadosAbertos->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtChamadosAbertos->execute();
            $resultChamados = $stmtChamadosAbertos->fetch(PDO::FETCH_ASSOC);

            if ($resultChamados['open_tickets'] > 0) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Não é possível inativar o funcionário. Ele possui chamados em andamento.'];
            }

            // 4. Inativar o usuário (marcar como is_active = FALSE)
            $queryInativarUser = "UPDATE " . $this->table_name . " SET is_active = FALSE WHERE id = :funcionario_id";
            $stmtInativarUser = $this->db->prepare($queryInativarUser);
            $stmtInativarUser->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtInativarUser->execute();

            // 5. Opcional: Remover a atribuição da empresa, se houver
            // Isso já foi verificado, mas como garantia extra, garantimos que client_id seja NULL
            $queryDesatribuir = "UPDATE " . $this->employees_table . " SET client_id = NULL WHERE user_id = :funcionario_id";
            $stmtDesatribuir = $this->db->prepare($queryDesatribuir);
            $stmtDesatribuir->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtDesatribuir->execute();


            $this->db->commit();
            return ['success' => true, 'message' => 'Funcionário inativado com sucesso!'];

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao inativar funcionário: " . $e->getMessage()); // Logar o erro real
            return ['success' => false, 'message' => 'Erro interno ao inativar funcionário.'];
        }
    }

    /**
     * NOVO: Ativa um funcionário (marcando is_active = TRUE).
     * @param int $funcionarioId O ID do funcionário a ser ativado.
     * @return array Retorna um array com 'success' (bool) e 'message' (string).
     */
    public function ativarFuncionario(int $funcionarioId): array
    {
        $this->db->beginTransaction();
        try {
            // 1. Verificar se o funcionário existe e é um funcionário TI/Cliente
            $queryFuncionario = "SELECT u.id, u.role FROM " . $this->table_name . " u
                                 WHERE u.id = :funcionario_id AND (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente') LIMIT 1";
            $stmtFuncionario = $this->db->prepare($queryFuncionario);
            $stmtFuncionario->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtFuncionario->execute();
            $funcionarioInfo = $stmtFuncionario->fetch(PDO::FETCH_ASSOC);

            if (!$funcionarioInfo) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Funcionário não encontrado ou não é um funcionário TI/Cliente válido.'];
            }

            // 2. Ativar o usuário (marcar como is_active = TRUE)
            $queryAtivarUser = "UPDATE " . $this->table_name . " SET is_active = TRUE WHERE id = :funcionario_id";
            $stmtAtivarUser = $this->db->prepare($queryAtivarUser);
            $stmtAtivarUser->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtAtivarUser->execute();

            $this->db->commit();
            return ['success' => true, 'message' => 'Funcionário ativado com sucesso!'];

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao ativar funcionário: " . $e->getMessage()); // Logar o erro real
            return ['success' => false, 'message' => 'Erro interno ao ativar funcionário.'];
        }
    }
}
