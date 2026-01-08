<?php
require_once '../Models/StaffModel.php';

class StaffController
{
    public function handleShiftAssignment($data)
    {
        $errors = [];


        if (empty($data['staffName']))
            $errors['staffName'] = "Staff name is required.";
        if (empty($data['shiftType']))
            $errors['shiftType'] = "Please select a shift.";
        if (empty($data['startTime']))
            $errors['startTime'] = "Start time is required.";
        if (empty($data['endTime']))
            $errors['endTime'] = "End time is required.";

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }


        $notify = isset($data['shiftNotification']) ? $data['shiftNotification'] : 'off';
        $inserted = saveShift($data['staffName'], $data['shiftType'], $data['startTime'], $data['endTime'], $notify);

        if ($inserted) {
            return [
                'success' => true,
                'message' => "Shift Assigned Successfully!\n\nStaff: " . $data['staffName'] . "\nShift: " . $data['shiftType'] . "\nTime: " . $data['startTime'] . " to " . $data['endTime']
            ];
        }
        return ['success' => false, 'errors' => ['db' => 'Database error occurred.']];
    }
}
?>