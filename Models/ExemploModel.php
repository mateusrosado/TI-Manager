<?php

class ExemploModel extends Model
{
    public function exemploDeUso($id)
    {
        $sql = $this->db->prepare("SELECT * FROM exemplo WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }
}
