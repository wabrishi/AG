<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model {
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create($name, $slug) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
        return $stmt->execute(['name' => $name, 'slug' => $slug]);
    }
}
