<?php
require_once 'db_connect.php';

function saveShift($name, $type, $start, $end, $notify)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO staff_schedules (staff_name, shift_type, start_time, end_time, notification_sent) VALUES (?, ?, ?, ?, ?)");

    $notifyVal = ($notify === 'on') ? 1 : 0;

    $stmt->bind_param("ssssi", $name, $type, $start, $end, $notifyVal);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>