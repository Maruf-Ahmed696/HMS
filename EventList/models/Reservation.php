<?php
class Reservation {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function reserve($eventId,$email,$seats){
        $stmt = $this->pdo->prepare(
            "SELECT available_seats FROM events WHERE id=?"
        );
        $stmt->execute([$eventId]);
        $event = $stmt->fetch();

        if(!$event || $event['available_seats'] < $seats){
            return false;
        }

        $this->pdo->prepare(
            "INSERT INTO reservations(event_id,email,seats)
             VALUES(?,?,?)"
        )->execute([$eventId,$email,$seats]);

        $this->pdo->prepare(
            "UPDATE events
             SET available_seats = available_seats - ?
             WHERE id=?"
        )->execute([$seats,$eventId]);

        return true;
    }
}
