<?php

class UserModel extends Model
{
    public function exemploDeUso($id)
    {
        $sql = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }
}