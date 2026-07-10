<?php

require_once __DIR__ . '/../core/Model.php';

class OrderModel extends Model
{
    public function createOrder($userId, $namaPenerima, $alamat, $noHp, $total, $cartItems)
    {
        $db = $this->getConnection();
        
        try {
            $db->beginTransaction();

            $stmtOrder = $db->prepare(
                "INSERT INTO orders (user_id, nama_penerima, alamat, no_hp, total, status) 
                 VALUES (?, ?, ?, ?, ?, 'pending')"
            );
            $stmtOrder->execute([$userId, $namaPenerima, $alamat, $noHp, $total]);
            
            $orderId = $db->lastInsertId();

            $stmtDetail = $db->prepare(
                "INSERT INTO order_details (order_id, product_id, qty, harga_satuan) 
                 VALUES (?, ?, ?, ?)"
            );
            
            $stmtUpdateStock = $db->prepare(
                "UPDATE products SET stok = stok - ? WHERE id = ?"
            );

            foreach ($cartItems as $item) {
                $stmtDetail->execute([
                    $orderId, 
                    $item['product']['id'], 
                    $item['qty'], 
                    $item['product']['harga']
                ]);
                

                $stmtUpdateStock->execute([
                    $item['qty'], 
                    $item['product']['id']
                ]);
            }

            $db->commit();
            return true;

        } catch (Exception $e) {
            error_log('Order creation failed: ' . $e->getMessage());
            $db->rollBack();
            return false;
        }
    }

    public function getOrdersByUser($userId)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare(
            "SELECT od.*, p.nama as nama_produk, p.gambar 
             FROM order_details od 
             JOIN products p ON od.product_id = p.id 
             WHERE od.order_id = ?"
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
