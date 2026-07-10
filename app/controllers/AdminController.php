<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/OrderModel.php';

class AdminController extends Controller
{
    private $productModel;
    private $userModel;
    private $orderModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }

    public function dashboard()
    {
        $totalProducts = $this->productModel->countProducts();
        $totalUsers = $this->userModel->countUsers();
        $totalOrders = $this->orderModel->countOrders();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        
        // For chart data (simple example, latest 10 orders)
        $recentOrders = array_slice($this->orderModel->getAllOrdersAdmin(), 0, 10);
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        
        // Group by simple date
        $grouped = [];
        foreach (array_reverse($recentOrders) as $order) {
            $date = date('M d', strtotime($order['created_at']));
            if (!isset($grouped[$date])) {
                $grouped[$date] = 0;
            }
            $grouped[$date] += $order['total'];
        }
        
        foreach ($grouped as $date => $total) {
            $chartData['labels'][] = $date;
            $chartData['data'][] = $total;
        }

        // We don't render view directly from here because views/dashboard/admin.php 
        // expects variables. Actually, we can return the data so admin.php can use it.
        return [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'chartData' => json_encode($chartData)
        ];
    }
}
