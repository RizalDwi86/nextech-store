<?php

require_once __DIR__ . '/../core/Model.php';

class OrderModel extends Model
{
    /**
     * Memasukkan data pesanan ke tabel orders dan order_details
     */
    public function createOrder($userId, $namaPenerima, $alamat, $noHp, $total, $cartItems)
    {
        $db = $this->getConnection();
        
        try {
            // Mulai transaksi database
            $db->beginTransaction();

            // 1. Insert ke tabel orders
            $stmtOrder = $db->prepare(
                "INSERT INTO orders (user_id, nama_penerima, alamat, no_hp, total, status) 
                 VALUES (?, ?, ?, ?, ?, 'pending')"
            );
            $stmtOrder->execute([$userId, $namaPenerima, $alamat, $noHp, $total]);
            
            // Dapatkan ID order yang baru dibuat
            $orderId = $db->lastInsertId();

            // 2. Insert ke tabel order_details dan kurangi stok produk
            $stmtDetail = $db->prepare(
                "INSERT INTO order_details (order_id, product_id, qty, harga_satuan) 
                 VALUES (?, ?, ?, ?)"
            );
            
            $stmtUpdateStock = $db->prepare(
                "UPDATE products SET stok = stok - ? WHERE id = ?"
            );

            foreach ($cartItems as $item) {
                // Insert detail
                $stmtDetail->execute([
                    $orderId, 
                    $item['product']['id'], 
                    $item['qty'], 
                    $item['product']['harga']
                ]);
                
                // Kurangi stok
                $stmtUpdateStock->execute([
                    $item['qty'], 
                    $item['product']['id']
                ]);
            }

            // Commit transaksi jika semua sukses
            $db->commit();
            return true;

        } catch (Exception $e) {
            // Rollback jika terjadi error
            error_log('Order creation failed: ' . $e->getMessage());
            $db->rollBack();
            return false;
        }
    }

    /**
     * Mengambil daftar pesanan berdasarkan User ID
     */
    public function getOrdersByUser($userId)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail item dari sebuah pesanan beserta nama produknya
     */
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
