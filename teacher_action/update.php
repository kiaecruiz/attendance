<?php
// action/handler.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $student_id = $data['student_id'];
    $action = $data['action'];

    // Perform the action and check if it was successful
    if (performAction($student_id, $action)) { // Replace with your actual logic
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Action could not be completed.']);
    }
    exit;
}

?>