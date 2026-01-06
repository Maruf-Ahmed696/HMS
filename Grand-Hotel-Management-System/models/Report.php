<?php
class Report {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function bookings(){
        return $this->pdo->query(
            "SELECT DATE(reserved_at) d, SUM(seats) v
             FROM reservations GROUP BY d"
        )->fetchAll();
    }
}
