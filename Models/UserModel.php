<?php
class UserModel extends Model
{
    private $table_name = "users";
    private $employees_table = "employees";
    private $tickets_table = "tickets";

    public function __construct()
    {
        parent::__construct();
    }

    public function findByCnpjAndVerifyPassword(string $cnpj, string $password): ?array
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE cnpj_login = :cnpj_login AND (role = 'admin' OR role = 'adm_cliente') AND is_active = TRUE LIMIT 1";
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

    public function findByEmailAndVerifyPassword(string $email, string $password): ?array
    {
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE email = :email AND (role = 'funcionario_ti' OR role = 'funcionario_cliente') AND is_active = TRUE LIMIT 1";
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

    public function getFuncionariosFiltered(?string $roleFilter = null, ?int $clientIdFilter = null, bool $onlyActive = true): array
    {
        $query = "SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.cpf,
                    u.role,
                    u.created_at,
                    u.is_active,
                    c.company_name,
                    e.client_id,
                    e.funcao
                  FROM " . $this->table_name . " u
                  LEFT JOIN " . $this->employees_table . " e ON u.id = e.user_id
                  LEFT JOIN clients c ON e.client_id = c.id
                  WHERE (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente')";

        $params = [];

        if ($onlyActive) {
            $query .= " AND u.is_active = TRUE";
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

    public function findByEmail(string $email): ?array
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByCpf(string $cpf): ?array
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE cpf = :cpf LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createFuncionarioTI(string $name, string $cpf, string $email, string $hashedPassword, string $role, string $funcao, ?int $clientId): ?int
    {
        $this->db->beginTransaction();
        try {
            $queryUser = "INSERT INTO " . $this->table_name . " (name, email, password, role, cpf, is_active)
                          VALUES (:name, :email, :password, :role, :cpf, TRUE)";
            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->bindParam(':name', $name);
            $stmtUser->bindParam(':email', $email);
            $stmtUser->bindParam(':password', $hashedPassword);
            $stmtUser->bindParam(':role', $role);
            $stmtUser->bindParam(':cpf', $cpf);
            $stmtUser->execute();

            $newUserId = $this->db->lastInsertId();

            if ($newUserId) {
                $queryEmployee = "INSERT INTO " . $this->employees_table . " (user_id, client_id, funcao)
                                  VALUES (:user_id, :client_id, :funcao)";
                $stmtEmployee = $this->db->prepare($queryEmployee);
                $stmtEmployee->bindParam(':user_id', $newUserId, PDO::PARAM_INT);
                if ($clientId !== null) {
                    $stmtEmployee->bindParam(':client_id', $clientId, PDO::PARAM_INT);
                } else {
                    $stmtEmployee->bindValue(':client_id', null, PDO::PARAM_NULL);
                }
                $stmtEmployee->bindParam(':funcao', $funcao);
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
                    e.funcao
                  FROM " . $this->table_name . " u
                  LEFT JOIN " . $this->employees_table . " e ON u.id = e.user_id
                  LEFT JOIN clients c ON e.client_id = c.id
                  WHERE u.id = :id AND (u.role = 'funcionario_ti' OR u.role = 'funcionario_cliente') LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateFuncionarioTI(int $id, string $name, string $cpf, string $email, ?string $hashedPassword, string $funcao, ?int $clientId): bool
    {
        $this->db->beginTransaction();
        try {
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

            $queryEmployee = "UPDATE " . $this->employees_table . " SET client_id = :client_id, funcao = :funcao WHERE user_id = :user_id";
            $stmtEmployee = $this->db->prepare($queryEmployee);
            $stmtEmployee->bindParam(':user_id', $id, PDO::PARAM_INT);
            if ($clientId !== null) {
                $stmtEmployee->bindParam(':client_id', $clientId, PDO::PARAM_INT);
            } else {
                $stmtEmployee->bindValue(':client_id', null, PDO::PARAM_NULL);
            }
            $stmtEmployee->bindParam(':funcao', $funcao);
            $stmtEmployee->execute();
            
            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao atualizar funcionário TI: " . $e->getMessage());
            return false;
        }
    }

    public function inativarFuncionario(int $funcionarioId): array
    {
        $this->db->beginTransaction();
        try {
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

            if ($funcionarioInfo['client_id'] !== null && $funcionarioInfo['client_id'] !== 0) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Não é possível inativar o funcionário. Ele está atribuído a uma empresa.'];
            }

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

            $queryInativarUser = "UPDATE " . $this->table_name . " SET is_active = FALSE WHERE id = :funcionario_id";
            $stmtInativarUser = $this->db->prepare($queryInativarUser);
            $stmtInativarUser->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtInativarUser->execute();

            $queryDesatribuir = "UPDATE " . $this->employees_table . " SET client_id = NULL WHERE user_id = :funcionario_id";
            $stmtDesatribuir = $this->db->prepare($queryDesatribuir);
            $stmtDesatribuir->bindParam(':funcionario_id', $funcionarioId, PDO::PARAM_INT);
            $stmtDesatribuir->execute();

            $this->db->commit();
            return ['success' => true, 'message' => 'Funcionário inativado com sucesso!'];

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao inativar funcionário: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno ao inativar funcionário.'];
        }
    }
}
