<?php
session_start();
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_client.php';

$client_id = isset($_SESSION['id']) ? $_SESSION['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
if ($client_id == 0 && isset($_SESSION['fio'])) {
    $q = mysqli_query($mysqli, "SELECT id FROM user WHERE fio = '{$_SESSION['fio']}'");
    if ($r = mysqli_fetch_assoc($q)) $client_id = $r['id'];
}

if (isset($_POST['send'])) {
    $date = date('Y-m-d');
    $trainer_id = $_POST['trainer_id'];
    $type_id = $_POST['workout_type_id'];
    $days = $_POST['visit_days'];
    $count = $_POST['total_workouts'];

    mysqli_query($mysqli, "INSERT INTO contracts (contract_date, client_id, trainer_id, workout_type_id, visit_days, total_workouts, paid_amount) 
                           VALUES ('$date', '$client_id', '$trainer_id', '$type_id', '$days', '$count', 0)");
    echo "<div class='container mt-3'><div class='alert alert-success'>Заявка отправлена регистратору!</div></div>";
}
?>

<div class="container mt-4">
    <h3>Подать заявку на тренировку</h3>
    <form method="POST">
        <div class="mb-2">
            Вид тренировки: 
            <select name="workout_type_id" required>
                <?php
                $wt = mysqli_query($mysqli, "SELECT id, title, price FROM workout_types");
                while ($r = mysqli_fetch_assoc($wt)) {
                    echo "<option value='{$r['id']}'>{$r['title']} ({$r['price']} руб.)</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-2">
            Тренер: 
            <select name="trainer_id" required>
                <?php
                $tr = mysqli_query($mysqli, "SELECT id, fio FROM user WHERE role = 'тренер'");
                while ($r = mysqli_fetch_assoc($tr)) {
                    echo "<option value='{$r['id']}'>{$r['fio']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-2">
            Желаемые дни: <input type="text" name="visit_days" placeholder="Пн, Ср, Пт" required>
        </div>
        <div class="mb-2">
            Количество тренировок: <input type="number" name="total_workouts" value="10" required>
        </div>
        <div class="d-flex justify-content-between align-items-center gap-3">
           <button type="submit" class="btn" style="background: linear-gradient(90deg, #b8eb44 0%, #64d148 100%); color: #1a1a1a; border: none; font-weight: 500;">Отправить заявку</button>
 </div>
          
    </form>
</div>


