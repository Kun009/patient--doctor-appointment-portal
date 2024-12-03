<?php
    
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    if($_POST){
        if(isset($_POST["booknow"])){
            $apponum=$_POST["apponum"];
            $scheduleid=$_POST["scheduleid"];
            $date=$_POST["date"];
            $scheduleid=$_POST["scheduleid"];
            $complaints=$_POST["complaints"];

            // Properly concatenate PHP variables into the SQL query
            // Properly concatenate PHP variables into the SQL query
$sql2 = "INSERT INTO appointment(pid, apponum, scheduleid, appodate, complaints) VALUES ('$userid', '$apponum', '$scheduleid', '$date', '$complaints')";

$result= $database->query($sql2);

// Redirect after data insertion
header("location: appointment.php?action=booking-added&id=".$apponum."&titleget=none");
        }
    }
?>
