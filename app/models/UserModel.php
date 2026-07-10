<?php

require_once __DIR__ . '/../core/Model.php';

class UserModel extends Model
{
    public function getAllUsers()
    {
        $db = $this->getConnection();
        $stmt = $db->query("SELECT id, nama, email, role, alamat, created_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUsers()
    {
        $db = $this->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function updateRole($id, $role)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    public function deleteUser($id)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
