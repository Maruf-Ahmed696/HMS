<?php
require_once '../Models/RoomModel.php';

class RoomController
{
    public function handleAddRoom($data)
    {
        $errors = [];

        if (empty($data['roomType']))
            $errors['roomType'] = "Please select a room type.";
        if (empty($data['roomNumber']))
            $errors['roomNumber'] = "Room number is required.";
        if (empty($data['roomAvailability']))
            $errors['roomAvailability'] = "Selection required.";
        if (empty($data['roomStatus']))
            $errors['roomStatus'] = "Please select room status.";

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $inserted = insertRoom($data['roomType'], $data['roomNumber'], $data['roomAvailability'], $data['roomStatus']);

        if ($inserted) {
            return [
                'success' => true,
                'message' => "Room added successfully!\n\nType: " . $data['roomType'] . "\nNumber: " . $data['roomNumber'] . "\nStatus: " . $data['roomStatus']
            ];
        }
        return ['success' => false, 'errors' => ['db' => 'Database error.']];
    }
}
?>