<?php
// Include your database connection script
include("db_connection.php");


// Retrieve qr parameter from GET request
if (isset($_GET['qr'])) {
    $qr = $_GET['qr'];

    // Prepare SQL query using a prepared statement
    $query = "SELECT lastName, student_id, firstName, grade_id, section_id,student_lrn, image FROM students WHERE qr = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $qr);
    $stmt->execute();

    // Check for errors during execution
    if ($stmt->error) {
        die('Error executing query: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the result into an associative array
        $row = $result->fetch_assoc();
        // Return details as JSON response
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        // Handle case where no student found
        echo json_encode(['error' => 'Student not found']);
    }
} else {
    // Handle case where qr parameter is missing
    echo json_encode(['error' => 'QR Code parameter missing']);
}

// Close prepared statement and database connection
$stmt->close();
$conn->close();
?>
