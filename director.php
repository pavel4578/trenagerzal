<?php
session_start();
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_manager.php';

$dir_id = isset($_SESSION['id']) ? $_SESSION['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
if ($dir_id == 0 && isset($_SESSION['fio'])) {
    $q = mysqli_query($mysqli, "SELECT id FROM user WHERE fio = '{$_SESSION['fio']}'");
    if ($r = mysqli_fetch_assoc($q)) {
        $dir_id = $r['id'];
    }
}

$date_from = isset($_GET['from']) ? $_GET['from'] : '2026-01-01';
$date_to = isset($_GET['to']) ? $_GET['to'] : '2026-12-31';
?>

<div class="container mt-4">
    <h2>Рабочее место Директора</h2>

    <?php
    $res = mysqli_query($mysqli, "SELECT fio, phone FROM user WHERE id = '$dir_id'");
    if ($row = mysqli_fetch_assoc($res)) {
        echo "<p><b>Директор:</b> {$row['fio']}  <b>Телефон:</b> {$row['phone']}</p>";
    }
    ?>

    <h4 class="mt-4">Сведения о работе тренеров за период</h4>
    <form method="GET" class="mb-3">
        С: <input type="date" name="from" value="<?php echo $date_from; ?>">
        По: <input type="date" name="to" value="<?php echo $date_to; ?>">
        <button type="submit" class="btn btn-primary btn-sm">Сформировать отчёт</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>№ п/п</th>
                <th>Ф.И.О. тренера</th>
                <th>Количество клиентов</th>
                <th>Количество тренировок</th>
                <th>Зарплата (руб.)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT u.fio,
                             COUNT(DISTINCT CASE WHEN v.id IS NOT NULL THEN c.client_id END) AS clients_count,
                             COUNT(v.id) AS workouts_count,
                             SUM(CASE WHEN v.id IS NOT NULL THEN wt.price * wt.price_factor ELSE 0 END) AS salary
                      FROM user u
                      LEFT JOIN contracts c ON u.id = c.trainer_id
                      LEFT JOIN workout_types wt ON c.workout_type_id = wt.id
                      LEFT JOIN visits v ON c.id = v.contract_id AND v.attended = 1 
                           AND v.visit_date BETWEEN '$date_from' AND '$date_to'
                      WHERE u.role = 'тренер'
                      GROUP BY u.id, u.fio";
            $res = mysqli_query($mysqli, $query);
            $i = 1;
            while ($row = mysqli_fetch_assoc($res)) {
                $salary = $row['salary'] ? number_format($row['salary'], 2, '.', '') : '0.00';
                echo "<tr>
                    <td>{$i}</td>
                    <td>{$row['fio']}</td>
                    <td>{$row['clients_count']}</td>
                    <td>{$row['workouts_count']}</td>
                    <td>{$salary}</td>
                </tr>";
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'temp/footer.php'; ?>
