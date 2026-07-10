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

    /**
     * Mengambil semua pesanan untuk admin
     */
    public function getAllOrdersAdmin()
    {
        $db = $this->getConnection();
        $stmt = $db->query(
            "SELECT o.*, u.nama as nama_user 
             FROM orders o 
             JOIN users u ON o.user_id = u.id 
             ORDER BY o.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Memperbarui status pesanan
     */
    public function updateOrderStatus($orderId, $status)
    {
        $db = $this->getConnection();
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }

    /**
     * Menghitung total order
     */
    public function countOrders()
    {
        $db = $this->getConnection();
        $stmt = $db->query("SELECT COUNT(*) FROM orders");
        return $stmt->fetchColumn();
    }

    /**
     * Menghitung total pendapatan dari pesanan yang 'selesai'
     */
    public function getTotalRevenue()
    {
        $db = $this->getConnection();
        $stmt = $db->query("SELECT SUM(total) FROM orders WHERE status = 'selesai'");
        return $stmt->fetchColumn() ?: 0;
    }
}
