<?php
include 'temp/bd.php';
include 'temp/head.php';
$contract_date=$_POST['contract_date'];
$client_id=$_POST['client_id'];
$trainer_id=$_POST['trainer_id'];
$workout_type_id=$_POST['workout_type_id'];
$visit_days=$_POST['visit_days'];
$total_workouts=$_POST['total_workouts'];
$paid_amount=$_POST['paid_amount'];
$sql="INSERT INTO `contracts`(`contract_date`, `client_id`, `trainer_id`, `workout_type_id`, `visit_days`, `total_workouts`, `paid_amount`) 
VALUES ('$contract_date','$client_id','$trainer_id','$workout_type_id','$visit_days','$total_workouts','$paid_amount')";
$res=$mysqli->query($sql);
header('Location: forma_dogovora.php');
?>