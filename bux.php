<?php
session_start();
include 'temp/bd.php';

$buh_id = isset($_SESSION['id']) ? $_SESSION['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
if ($buh_id == 0 && isset($_SESSION['fio'])) {
    $q = mysqli_query($mysqli, "SELECT id FROM user WHERE fio = '{$_SESSION['fio']}'");
    if ($r = mysqli_fetch_assoc($q)) {
        $buh_id = $r['id'];
    }
}

// Внесение оплаты (доплаты) по договору
if (isset($_POST['add_payment'])) {
    $contract_id = (int)$_POST['contract_id'];
    $sum = (float)$_POST['pay_sum'];

    // Проверяем текущий долг по договору
    $check_q = mysqli_query($mysqli, "SELECT c.total_workouts, c.paid_amount, wt.price 
                                      FROM contracts c 
                                      JOIN workout_types wt ON c.workout_type_id = wt.id 
                                      WHERE c.id = '$contract_id'");
    if ($c_row = mysqli_fetch_assoc($check_q)) {
        $debt = ($c_row['total_workouts'] * $c_row['price']) - $c_row['paid_amount'];

        if ($sum > 0 && $sum <= $debt) {
            mysqli_query($mysqli, "UPDATE contracts SET paid_amount = paid_amount + '$sum' WHERE id = '$contract_id'");
            header("Location: bux.php?success=1");
            exit;
        } else {
            header("Location: bux.php?error=1");
            exit;
        }
    }
}

include 'temp/head.php';
include 'temp/nav_buhgaltera.php';

// Вывод уведомлений после редиректа
if (isset($_GET['success'])) {
    echo "<div class='container mt-3'><div class='alert alert-success'>Оплата успешно проведена!</div></div>";
}
if (isset($_GET['error'])) {
    echo "<div class='container mt-3'><div class='alert alert-danger'>Ошибка: сумма должна быть больше 0 и не превышать текущий долг!</div></div>";
}

$date_from = isset($_GET['from']) ? $_GET['from'] : '2026-01-01';
$date_to = isset($_GET['to']) ? $_GET['to'] : '2026-12-31';
?>

<div class="container mt-4">
    <h2>Рабочее место Бухгалтера</h2>

    <?php
    $res = mysqli_query($mysqli, "SELECT fio, phone FROM user WHERE id = '$buh_id'");
    if ($row = mysqli_fetch_assoc($res)) {
        echo "<p><b>Бухгалтер:</b> {$row['fio']} <b>Телефон:</b> {$row['phone']}</p>";
    }
    ?>

    <h4 class="mt-4">Провести оплату от клиента по договору</h4>
    <form method="POST">
        <div class="mb-2">
            Договор: 
            <select name="contract_id" required>
                <?php
                $c_sql = "SELECT c.id, u.fio, c.total_workouts, c.paid_amount, wt.price 
                          FROM contracts c
                          JOIN user u ON c.client_id = u.id
                          JOIN workout_types wt ON c.workout_type_id = wt.id";
                $c_res = mysqli_query($mysqli, $c_sql);
                while ($r = mysqli_fetch_assoc($c_res)) {
                    $debt = ($r['total_workouts'] * $r['price']) - $r['paid_amount'];
                    if ($debt <= 0) {
                        continue; // Пропускаем уже полностью оплаченные договоры
                    }
                    echo "<option value='{$r['id']}'>Договор №{$r['id']} — {$r['fio']} (Долг: {$debt} руб.)</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-2">
            Сумма внесения (руб.): <input type="number" step="0.01" min="0.01" name="pay_sum" required>
        </div>
      <div class="d-flex justify-content-between align-items-center gap-3">
           <button type="submit" class="btn" style="background: linear-gradient(90deg, #b8eb44 0%, #64d148 100%); color: #1a1a1a; border: none; font-weight: 500;">Провести оплату</button>
</div>
    </form>

    <hr class="my-4">

    <h4>Финансовый отчёт по договорам за период</h4>
    <form method="GET" class="mb-3">
        С: <input type="date" name="from" value="<?php echo $date_from; ?>">
        По: <input type="date" name="to" value="<?php echo $date_to; ?>">
        <button type="submit" class="btn btn-secondary btn-sm">Сформировать</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>№ договора</th>
                <th>Дата</th>
                <th>Ф.И.О. клиента</th>
                <th>Вид тренировки</th>
                <th>Начислено</th>
                <th>Оплачено</th>
                <th>Задолженность</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT c.id, c.contract_date, cl.fio AS client_fio, wt.title,
                             c.total_workouts, c.paid_amount, wt.price
                      FROM contracts c
                      JOIN user cl ON c.client_id = cl.id
                      JOIN workout_types wt ON c.workout_type_id = wt.id
                      WHERE c.contract_date BETWEEN '$date_from' AND '$date_to'
                      ORDER BY c.id DESC";
            $res = mysqli_query($mysqli, $query);
            $total_accrued = 0;
            $total_paid = 0;
            $total_debt = 0;
            while ($row = mysqli_fetch_assoc($res)) {
                $accrued = $row['total_workouts'] * $row['price'];
                $debt = $accrued - $row['paid_amount'];
                $total_accrued += $accrued;
                $total_paid += $row['paid_amount'];
                $total_debt += $debt;
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['contract_date']}</td>
                    <td>{$row['client_fio']}</td>
                    <td>{$row['title']}</td>
                    <td>{$accrued}</td>
                    <td>{$row['paid_amount']}</td>
                    <td>{$debt}</td>
                </tr>";
            }
            ?>
            <tr>
                <th colspan="4">ИТОГО ЗА ПЕРИОД:</th>
                <th><?php echo $total_accrued; ?></th>
                <th><?php echo $total_paid; ?></th>
                <th><?php echo $total_debt; ?></th>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'temp/footer.php'; ?>
