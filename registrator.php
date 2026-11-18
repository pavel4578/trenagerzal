<?php
session_start();
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_registrator.php';

// Определяем ФИО регистратора из сессии
$reg_id = isset($_SESSION['id']) ? $_SESSION['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
if ($reg_id == 0 && isset($_SESSION['fio'])) {
    $q = mysqli_query($mysqli, "SELECT id FROM user WHERE fio = '{$_SESSION['fio']}'");
    if ($r = mysqli_fetch_assoc($q)) {
        $reg_id = $r['id'];
    }
}

// Обработка формы заключения нового договора
if (isset($_POST['add_contract'])) {
    $date = $_POST['contract_date'];
    $client_id = $_POST['client_id'];
    $trainer_id = $_POST['trainer_id'];
    $type_id = $_POST['workout_type_id'];
    $days = $_POST['visit_days'];
    $count = $_POST['total_workouts'];
    $paid = $_POST['paid_amount'];

    $sql = "INSERT INTO contracts (contract_date, client_id, trainer_id, workout_type_id, visit_days, total_workouts, paid_amount) 
            VALUES ('$date', '$client_id', '$trainer_id', '$type_id', '$days', '$count', '$paid')";
    mysqli_query($mysqli, $sql);
    echo "<div class='container mt-3'><div class='alert alert-success'>Договор успешно заключен!</div></div>";
}

$date_from = isset($_GET['from']) ? $_GET['from'] : '2026-01-01';
$date_to = isset($_GET['to']) ? $_GET['to'] : '2026-12-31';
?>

<div class="container mt-4">
    <h2>Рабочее место Регистратора</h2>

    <?php
    $res = mysqli_query($mysqli, "SELECT fio, phone FROM user WHERE id = '$reg_id'");
    if ($row = mysqli_fetch_assoc($res)) {
        echo "<p><b>Регистратор:</b> {$row['fio']} | <b>Телефон:</b> {$row['phone']}</p>";
    }
    ?>

    <h4 class="mt-4">Заключить новый договор</h4>
    <form method="POST">
        <div class="mb-2">
            Дата договора: <input type="date" name="contract_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="mb-2">
            Клиент: 
            <select name="client_id" required>
                <?php
                $cl = mysqli_query($mysqli, "SELECT id, fio FROM user WHERE role = 'клиент'");
                while ($r = mysqli_fetch_assoc($cl)) {
                    echo "<option value='{$r['id']}'>{$r['fio']}</option>";
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
            Дни посещения: <input type="text" name="visit_days" placeholder="Пн, Ср, Пт" required>
        </div>
        <div class="mb-2">
            Кол-во тренировок: <input type="number" name="total_workouts" value="10" required>
        </div>
        <div class="mb-2">
            Оплачено (руб.): <input type="number" step="0.01" name="paid_amount" value="5000" required>
        </div>
        <button type="submit" name="add_contract" class="btn btn-primary mt-2">Заключить договор</button>
    </form>

    <hr class="my-4">

    <h4>Сведения об оплате за период</h4>
    <form method="GET" class="mb-3">
        С: <input type="date" name="from" value="<?php echo $date_from; ?>">
        По: <input type="date" name="to" value="<?php echo $date_to; ?>">
        <button type="submit" class="btn btn-secondary btn-sm">Показать</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>№ договора</th>
                <th>Дата</th>
                <th>Ф.И.О. клиента</th>
                <th>Кол-во посещений</th>
                <th>Тренер</th>
                <th>Начислено</th>
                <th>Оплачено</th>
                <th>Задолженность</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT c.id, c.contract_date, cl.fio AS client_fio, tr.fio AS trainer_fio, 
                             c.total_workouts, c.paid_amount, wt.price,
                             (SELECT COUNT(*) FROM visits v WHERE v.contract_id = c.id AND v.attended = 1) AS fact_visits
                      FROM contracts c
                      JOIN user cl ON c.client_id = cl.id
                      JOIN user tr ON c.trainer_id = tr.id
                      JOIN workout_types wt ON c.workout_type_id = wt.id
                      WHERE c.contract_date BETWEEN '$date_from' AND '$date_to'
                      ORDER BY c.id DESC";
            $res = mysqli_query($mysqli, $query);
            while ($row = mysqli_fetch_assoc($res)) {
                $accrued = $row['total_workouts'] * $row['price'];
                $debt = $accrued - $row['paid_amount'];
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['contract_date']}</td>
                    <td>{$row['client_fio']}</td>
                    <td>{$row['fact_visits']} из {$row['total_workouts']}</td>
                    <td>{$row['trainer_fio']}</td>
                    <td>{$accrued}</td>
                    <td>{$row['paid_amount']}</td>
                    <td>{$debt}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'temp/footer.php'; ?>
