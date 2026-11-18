<?php
include 'temp/head.php';
include 'temp/bd.php';
session_start();
if(!empty($_SESSION['role'])){
    $role = $_SESSION['role'];
    if($role == 'клиент'){
        include 'temp/nav_client.php';
    }
    if($role == 'директор'){
        include 'temp/nav_manager.php';
    }
    if($role == 'регистратор'){
        include 'temp/nav_registrator.php';
    }
     if($role == 'бухгалтер'){
        include 'temp/nav_buhgaltera.php';
    }
    if($role == 'тренер'){
        include 'temp/nav_trenera.php';
    }
} else {
    include 'temp/nav.php';
}
?>


<div class="container mt-4">
    <div class="p-4 mb-4 bg-light border rounded">
        <h2>Добро пожаловать в тренажерный зал!</h2>
        <p>Информационная система учета тренировок, расписания и абонементов. Для заключения договора и записи на занятия обратитесь к регистратору или войдите в личный кабинет.</p>
        <p class="mb-0"><b>Режим работы тренажерного зала:</b> Понедельник – Воскресенье, с 08:00 до 22:00 (без выходных).</p>
    </div>

    <h3 class="mb-3">Виды тренировок и стоимость</h3>
    <div class="row">
        <?php
        $sql = "SELECT * FROM workout_types LIMIT 6";
        $res = mysqli_query($mysqli, $sql);
        
        while ($row = mysqli_fetch_assoc($res)) {
            $img = !empty($row['img']) ? $row['img'] : 'img/0.jpg';
            echo "
            <div class='col-md-4 mb-4'>
                <div class='card h-100'>
                    <img src='{$img}' class='card-img-top' alt='{$row['title']}' style='height: 200px; object-fit: cover;'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$row['title']}</h5>
                        <p class='card-text'>Стоимость одного занятия: <b>{$row['price']} руб.</b></p>
                    </div>
                </div>
            </div>";
        }
        ?>
    </div>

    <h3 class="mt-4 mb-3">Расписание работы тренеров</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ф.И.О. тренера</th>
                <th>Дни работы</th>
                <th>Часы приема</th>
                <th>Контактный телефон</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $t_sql = "SELECT fio, phone FROM user WHERE role = 'тренер'";
            $t_res = mysqli_query($mysqli, $t_sql);
            while ($t_row = mysqli_fetch_assoc($t_res)) {
                echo "<tr>
                    <td>{$t_row['fio']}</td>
                    <td>Пн, Ср, Пт, Сб</td>
                    <td>09:00 – 21:00</td>
                    <td>{$t_row['phone']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'temp/footer.php'; ?>
