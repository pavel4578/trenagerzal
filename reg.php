<?php
include 'temp/bd.php';
if (isset($_POST['login'])) {
    $fio = $_POST['fio'];
    $login = $_POST['login'];
    $password = $_POST['password']; 
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = 'клиент'; 
    $sql = "INSERT INTO `user` (`login`, `password`, `email`, `phone`, `role`, `fio`) 
            VALUES ('$login', '$password', '$email', '$phone', '$role', '$fio')";


    $res = $mysqli->query($sql);

    if ($res) {

        header('Location: formavto.php');
        exit();
    } else {

        die("Ошибка MySQL: " . $mysqli->error);
    }
} else {
    header('Location: formavto.php');
    exit();
}
?>
