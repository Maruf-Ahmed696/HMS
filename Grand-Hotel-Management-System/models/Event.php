<?php
class Event {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function create($title,$desc,$date,$total){
        $stmt = $this->pdo->prepare(
            "INSERT INTO events
            (title,description,event_date,total_seats,available_seats)
            VALUES(?,?,?,?,?)"
        );
        return $stmt->execute([$title,$desc,$date,$total,$total]);
    }

    public function all(){
        return $this->pdo->query(
            "SELECT * FROM events ORDER BY event_date"
        )->fetchAll();
    }
}
