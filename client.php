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

?>

<div class="container mt-4">
    <h2>Личный кабинет клиента</h2>
    
    <?php
    $res = mysqli_query($mysqli, "SELECT fio, phone, email FROM user WHERE id = '$client_id'");
    if ($row = mysqli_fetch_assoc($res)) {
        echo "<p><b>Клиент:</b> {$row['fio']}  <b>Телефон:</b> {$row['phone']}</p>";
    }
    ?>

    <h4 class="mt-4">Мои договоры и оплата</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>№ договора</th>
                <th>Дата</th>
                <th>Вид тренировки</th>
                <th>Тренер</th>
                <th>Дни посещения</th>
                <th>Начислено (руб.)</th>
                <th>Оплачено (руб.)</th>
                <th>Остаток долга</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT c.id, c.contract_date, c.visit_days, c.total_workouts, c.paid_amount,
                           wt.title AS workout_title, wt.price, u.fio AS trainer_fio
                    FROM contracts c
                    JOIN workout_types wt ON c.workout_type_id = wt.id
                    JOIN user u ON c.trainer_id = u.id
                    WHERE c.client_id = '$client_id'";
            $res = mysqli_query($mysqli, $sql);
            while ($row = mysqli_fetch_assoc($res)) {
                $accrued = $row['total_workouts'] * $row['price'];
                $debt = $accrued - $row['paid_amount'];
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['contract_date']}</td>
                    <td>{$row['workout_title']} ({$row['total_workouts']} зан.)</td>
                    <td>{$row['trainer_fio']}</td>
                    <td>{$row['visit_days']}</td>
                    <td>{$accrued}</td>
                    <td>{$row['paid_amount']}</td>
                    <td>{$debt}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <h4 class="mt-4">Моя программа тренировок и отметки посещений</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Дата тренировки</th>
                <th>№ договора</th>
                <th>Программа тренировок (от тренера)</th>
                <th>Статус посещения</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $v_sql = "SELECT v.visit_date, v.contract_id, v.program, v.attended
                      FROM visits v
                      JOIN contracts c ON v.contract_id = c.id
                      WHERE c.client_id = '$client_id'
                      ORDER BY v.visit_date DESC";
            $v_res = mysqli_query($mysqli, $v_sql);
            while ($v_row = mysqli_fetch_assoc($v_res)) {
                $status = $v_row['attended'] == 1 ? "Присутствовал" : "Пропуск";
                echo "<tr>
                    <td>{$v_row['visit_date']}</td>
                    <td>№ {$v_row['contract_id']}</td>
                    <td>{$v_row['program']}</td>
                    <td>{$status}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'temp/footer.php'; ?>
