<?php
include 'temp/bd.php';
include 'temp/head.php';
include 'temp/nav_manager.php';
$startDate = $_GET['start'] ?? '2026-01-01';
$endDate = $_GET['end'] ?? '2027-01-01';
?>
<h1 class="mt-3 mb-3">Сведения о работе тренеров</h1>
<form method="get">
    <label>Сведения о работе тренеров за период c: <input type="date" name="start" value="<?php echo $startDate; ?>" required></label>
    <label>по: <input type="date" name="end" value="<?php echo $endDate; ?>" required></label>
    <button type="submit">Показать</button>
</form>

<table>
    <thead>
    <tr>
        <th>№ п/п</th>
        <th>Ф.И.О. тренера</th>
        <th>Кол-во клиентов</th>
        <th>Кол-во тренировок</th>
        <th>Зарплата (30%)</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    $sql="SELECT user.fio AS trainer_name, 
    COUNT(DISTINCT contracts.client_id) AS client_count, 
    COUNT(visits.id) AS workout_count, 
    SUM(visits.id) AS total_visits_id, 
    SUM(workout_types.price * workout_types.price_factor) AS salary 
    FROM user JOIN contracts ON user.id = contracts.trainer_id 
    JOIN visits ON contracts.id = visits.contract_id 
    JOIN workout_types ON contracts.workout_type_id = workout_types.id 
    WHERE user.role = 'тренер' AND visits.visit_date BETWEEN '$startDate' AND '$endDate' 
    GROUP BY user.id, user.fio 
    ORDER BY user.fio";
    $res=$mysqli->query($sql);
    foreach($res as $row){
        echo '<tr>
            <th>'.$numb.'</th>
            <td>'.$row['trainer_name'].'</td>
            <td>'.$row['client_count'].'</td>
            <td>'.$row['workout_count'].'</td>
            <td>'.number_format($row['salary'], 2, '.', ' ').'руб.</td>
        </tr>';
        $numb++;
    }   
    ?>
    </tbody>
</table>
</body>
