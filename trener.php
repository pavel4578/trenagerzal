<?php
session_start();
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_trenera.php';

// Определяем ID авторизованного тренера из сессии
$trainer_id = isset($_SESSION['id']) ? $_SESSION['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
if ($trainer_id == 0 && isset($_SESSION['fio'])) {
    $q = mysqli_query($mysqli, "SELECT id FROM user WHERE fio = '{$_SESSION['fio']}'");
    if ($r = mysqli_fetch_assoc($q)) {
        $trainer_id = $r['id'];
    }
}

// Сохранение программы тренировок и отметки о посещении
if (isset($_POST['save_visit'])) {
    $contract_id = $_POST['contract_id'];
    $visit_date = $_POST['visit_date'];
    $program = $_POST['program'];
    $attended = isset($_POST['attended']) ? 1 : 0;

    $sql = "INSERT INTO visits (contract_id, visit_date, program, attended) 
            VALUES ('$contract_id', '$visit_date', '$program', '$attended')";
    mysqli_query($mysqli, $sql);
    echo "<div class='container mt-3'><div class='alert alert-success'>Данные о тренировке сохранены!</div></div>";
}
?>

<div class="container mt-4">
    <h2>Рабочее место Тренера</h2>

    <?php
    $res = mysqli_query($mysqli, "SELECT fio, phone FROM user WHERE id = '$trainer_id'");
    if ($row = mysqli_fetch_assoc($res)) {
        echo "<p><b>Тренер:</b> {$row['fio']}  <b>Телефон:</b> {$row['phone']}</p>";
    }
    ?>

    <h4 class="mt-4">Составить программу и отметить посещение</h4>
    <form method="POST">
        <div class="mb-2">
            Клиент (договор): 
            <select name="contract_id" required>
                <?php
                // Выбираем только договоры этого тренера
                $c_sql = "SELECT c.id, u.fio, wt.title 
                          FROM contracts c
                          JOIN user u ON c.client_id = u.id
                          JOIN workout_types wt ON c.workout_type_id = wt.id
                          WHERE c.trainer_id = '$trainer_id'";
                $c_res = mysqli_query($mysqli, $c_sql);
                while ($c_row = mysqli_fetch_assoc($c_res)) {
                    echo "<option value='{$c_row['id']}'>Договор №{$c_row['id']} — {$c_row['fio']} ({$c_row['title']})</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-2">
            Дата тренировки: <input type="date" name="visit_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="mb-2">
            Программа тренировок: <input type="text" name="program" placeholder="Например: Разминка, жим лежа, кардио" required>
        </div>
        <div class="mb-2">
            <label><input type="checkbox" name="attended" checked> Клиент присутствовал на тренировке</label>
        </div>
      <div class="d-flex justify-content-between align-items-center gap-3">
           <button type="submit" class="btn" style="background: linear-gradient(90deg, #b8eb44 0%, #64d148 100%); color: #1a1a1a; border: none; font-weight: 500;">Сохранить запись</button>

              </div>
    </form>

    <hr class="my-4">

    <h4>Журнал тренировок и посещений моих клиентов</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Дата</th>
                <th>№ договора</th>
                <th>Ф.И.О. клиента</th>
                <th>Вид тренировки</th>
                <th>Программа тренировок</th>
                <th>Отметка</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $v_sql = "SELECT v.visit_date, v.contract_id, v.program, v.attended, u.fio AS client_fio, wt.title
                      FROM visits v
                      JOIN contracts c ON v.contract_id = c.id
                      JOIN user u ON c.client_id = u.id
                      JOIN workout_types wt ON c.workout_type_id = wt.id
                      WHERE c.trainer_id = '$trainer_id'
                      ORDER BY v.visit_date DESC";
            $v_res = mysqli_query($mysqli, $v_sql);
            while ($v_row = mysqli_fetch_assoc($v_res)) {
                $status = $v_row['attended'] == 1 ? "Пришел" : "Не пришел";
                echo "<tr>
                    <td>{$v_row['visit_date']}</td>
                    <td>№ {$v_row['contract_id']}</td>
                    <td>{$v_row['client_fio']}</td>
                    <td>{$v_row['title']}</td>
                    <td>{$v_row['program']}</td>
                    <td>{$status}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'temp/footer.php'; ?>
