<?php
include("../connection.php");
// Check connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Retrieve patient name from the POST request
$pname = $_POST["pname"];

// Use the $pname to find the patient's PID from the patient table
$sql = "SELECT pid FROM patient WHERE pname = ?";

$stmt = $database->prepare($sql);
$stmt->bind_param("s", $pname);

if ($stmt->execute()) {
    $stmt->bind_result($pid);
    $stmt->fetch();
    $stmt->close();

    if ($pid) {
        // Use the PID to find complaints in the appointment table
        $complaints = [];
        $sql = "SELECT complaints FROM appointment WHERE pid = ?";

        $stmt = $database->prepare($sql);
        $stmt->bind_param("i", $pid);

        if ($stmt->execute()) {
            $stmt->bind_result($complaint);

            while ($stmt->fetch()) {
                $complaints[] = $complaint;
            }

            $stmt->close();

            if (!empty($complaints)) {
                // Return the complaints as a JSON response
                echo json_encode($complaints);
            } else {
                echo "No complaints found for $pname.";
            }
        } else {
            echo "Error executing SQL query: " . $database->error;
        }
    } else {
        echo "Patient not found: $pname.";
    }
} else {
    echo "Error executing SQL query: " . $database->error;
}

// Close the database connection
$database->close();
?>
