<?php 
session_start();
include 'temp/bd.php';
$login = $_POST['login'];
$password = $_POST['password'];
$sql = "SELECT * FROM user WHERE login = '$login' AND password = '$password'";
$res = $mysqli->query($sql);

if ($res && $res->num_rows > 0) {
    $user = $res->fetch_assoc();
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['fio'] = $user['fio'];
    $_SESSION['role'] = $user['role'];
    
    header('Location: index.php');
    exit;
} else {
    header('Location: formavto.php?error=1');
    exit;
}
?>
