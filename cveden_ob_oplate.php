<?php
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_manager.php';
$startDate = $_GET['start'] ?? '2026-01-01';
$endDate = $_GET['end'] ?? '2027-01-01';
?>
<form method="get">
    <label>Сведения об оплате за период за период c: <input type="date" name="start" value="<?php echo $startDate; ?>" required></label>
    <label>gо: <input type="date" name="end" value="<?php echo $endDate; ?>" required></label>
    <button type="submit">Показать</button>
</form>

<table>
    <thead>
    <tr>
        <th>№ договора</th>
        <th>Ф.И.О. клиента</th>
        <th>Кол-во посещений</th>
        <th>Тренер</th>
        <th>Начислено</th>
        <th>Оплачено</th>
        <th>Задолжность</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    $numb=1;
    $contractDate = '2026-11-20';
    $sql="SELECT 
    contracts.id AS contract_id,
    (SELECT fio FROM user WHERE id = contracts.client_id) AS client_name, 
    (SELECT fio FROM user WHERE id = contracts.trainer_id) AS trainer_name, 
    contracts.paid_amount,
    (contracts.total_workouts * workout_types.price) AS accrued_amount,
    (SELECT COUNT(*) 
    FROM visits 
    WHERE visits.contract_id = contracts.id 
    AND visits.visit_date BETWEEN '$startDate' AND '$endDate'
    ) AS visit_count 
FROM contracts 
JOIN workout_types ON contracts.workout_type_id = workout_types.id
WHERE contracts.contract_date <= '$contractDate'";
    $res=$mysqli->query($sql);
    foreach($res as $row){
        $accrued = $row['accrued_amount']; 
        $paid = $row['paid_amount']; 
        $zadolzh = $accrued - $paid;
        echo '<tr>
            <th>'.$numb.'</th>
            <td>'.$row['client_name'].'</td>
            <td>'.$row['visit_count'].'</td>
            <td>'.$row['trainer_name'].'</td>
            <td>'.$row['accrued_amount'].'руб.</td>
            <td>'.$row['paid_amount'].'руб.</td>
            <td>'.$zadolzh.'руб.</td>
        </tr>';
        $numb++;
    }   
    ?>
    </tbody>
</table>
</body>
