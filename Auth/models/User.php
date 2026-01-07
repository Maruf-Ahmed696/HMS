<?php
class User {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        $stmt = $this->pdo->query(
            "SELECT id, name, email, username, role, status, created_at 
             FROM users ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function update($id, $name, $email, $role){
        $stmt = $this->pdo->prepare(
            "UPDATE users SET name=?, email=?, role=? WHERE id=?"
        );
        return $stmt->execute([$name, $email, $role, $id]);
    }

    public function changeStatus($id, $status){
        $stmt = $this->pdo->prepare(
            "UPDATE users SET status=? WHERE id=?"
        );
        return $stmt->execute([$status, $id]);
    }

    public function delete($id){
        $stmt = $this->pdo->prepare(
            "DELETE FROM users WHERE id=?"
        );
        return $stmt->execute([$id]);
    }
}
