<?php
// File: TI-MANAGER/Models/AdmModel.php

class AdmModel extends Model
{
    private $table_name = "users";

    public function __construct()
    {
        parent::__construct();
    }

    public function findByCnpjAndVerifyPassword(string $cnpj, string $password): ?array
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        $query = "SELECT id, name, email, password, role FROM " . $this->table_name . " WHERE cnpj_login = :cnpj_login AND (role = 'admin' OR role = 'adm_cliente') LIMIT 0,1";

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
}
