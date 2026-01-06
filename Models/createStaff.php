<?php

require_once '../Models/db_Connect.php';

class CreateStaffModel
{


    public function insertStaffSchedule($staffName, $shiftType, $startTime, $endTime, $shiftNotification)
    {
        global $conn;


        $stmt = $conn->prepare("INSERT INTO staff_schedule (staff_name, shift_type, start_time, end_time, shift_notification) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $staffName, $shiftType, $startTime, $endTime, $shiftNotification);


        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>