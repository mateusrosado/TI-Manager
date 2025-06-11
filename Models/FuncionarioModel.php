<?php

class FuncionarioModel extends Model
{
    private $table_name = "users";

    public function __construct()
    {
        parent::__construct();
    }

    public function findByEmailAndVerifyPassword(string $email, string $password): ?array
    {
        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE email = :email AND (role = 'funcionario_ti' OR role = 'funcionario_cliente') LIMIT 0,1";
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
}
