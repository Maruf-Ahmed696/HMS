<?php
class Notification {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function create($type,$message){
        $stmt = $this->pdo->prepare(
            "INSERT INTO notifications(type,message) VALUES(?,?)"
        );
        return $stmt->execute([$type,$message]);
    }

    public function all(){
        return $this->pdo->query(
            "SELECT * FROM notifications ORDER BY id DESC"
        )->fetchAll();
    }
}
