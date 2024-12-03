<?php

include("../connection.php");

// Check the connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Get pname, diagnosis, and prescription from POST data
$pname = $_POST["pname"];
$diagnosis = $_POST["diagnosis"];
$prescription = $_POST["prescription"];

// Find PID by pname in the patient table
$query = "SELECT pid FROM patient WHERE pname = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("s", $pname);
$stmt->execute();
$stmt->bind_result($pid);

if ($stmt->fetch()) {
    // Close the first statement
    $stmt->close();

    // Prepare and execute an SQL query to insert the diagnosis and prescription into the reports table
    $sql = "INSERT INTO reports (pname, pid, diagnosis, prescription) VALUES (?, ?, ?, ?)";
    $stmt2 = $database->prepare($sql);

    if ($stmt2) {
        // Bind the parameters and execute the query
        $stmt2->bind_param("siss", $pname, $pid, $diagnosis, $prescription);
        if ($stmt2->execute()) {
            echo "Diagnosis and prescription recorded successfully for patient: " . $pname;
        } else {
            echo "Error executing the query: " . $stmt2->error;
        }

        // Close the second statement
        $stmt2->close();
    } else {
        echo "Error preparing the statement: " . $database->error;
    }
} else {
    echo "Error: Patient not found.";
}

// Close the database connection
$database->close();
?>
