<?php

require_once __DIR__ . '/../core/Model.php';

class ProductModel extends Model
{
    public function getAllProducts($search = '', $limit = null, $offset = 0)
    {
        $db = $this->getConnection();
        
        $sql = "SELECT * FROM products";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE nama LIKE ?";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY id DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $stmt = $db->prepare($sql);
            
            $i = 1;
            foreach ($params as $param) {
                $stmt->bindValue($i++, $param, PDO::PARAM_STR);
            }
            $stmt->bindValue($i++, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue($i++, (int)$offset, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    public function getProductById($id)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createProduct($data)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare(
            "INSERT INTO products (nama, kategori, harga, stok, deskripsi, gambar) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['nama'],
            $data['kategori'] ?? null,
            $data['harga'],
            $data['stok'],
            $data['deskripsi'],
            $data['gambar']
        ]);
    }
    
    public function updateProduct($id, $data)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare(
            "UPDATE products SET nama = ?, kategori = ?, harga = ?, stok = ?, deskripsi = ?, gambar = ? 
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['nama'],
            $data['kategori'] ?? null,
            $data['harga'],
            $data['stok'],
            $data['deskripsi'],
            $data['gambar'],
            $id
        ]);
    }
    
    public function deleteProduct($id)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function countProducts($search = '')
    {
        $db = $this->getConnection();
        $sql = "SELECT COUNT(*) FROM products";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " WHERE nama LIKE ?";
            $params[] = "%$search%";
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
