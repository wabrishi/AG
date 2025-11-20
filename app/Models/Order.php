<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model {
    public function create($userId, $total, $shippingAddress, $paymentMethod) {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status) VALUES (:user_id, :total, :address, :payment, 'pending')");
        $stmt->execute([
            'user_id' => $userId,
            'total' => $total,
            'address' => $shippingAddress,
            'payment' => $paymentMethod
        ]);
        return $this->db->lastInsertId();
    }

    public function addOrderItem($orderId, $productId, $quantity, $price) {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->execute([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getItems($orderId) {
        $stmt = $this->db->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    public function getByUser($userId) {
         $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
         $stmt->execute(['user_id' => $userId]);
         return $stmt->fetchAll();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT o.*, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }
}
