<?php
include 'temp/bd.php';
include 'temp/nav_registrator.php';
include 'temp/head.php';
session_start();
?>
<h1 class="mt-3 mb-3">Заключение договора</h1>
<form method="POST" action="d_forma_dogovora.php">
    <div class="mb-3">
    <label for="contract_date" class="form-label">Дата:</label>
    <input type="date" class="form-control" name="contract_date" id="contract_date" required>
    </div>
    <div class="mb-3">
<label for="client_id" class="form-label"> Клиент:</label>
    <select class="form-select" name="client_id" aria-label="Default select example">
  <?php 
  $sql=$mysqli->query("SELECT * FROM `user` WHERE `role` = 'клиент'");
  foreach($sql as $clients){
    echo '<option value="'.$clients['id'].'">'.$clients['fio'].'</option>';
  }
   ?>
</select>
</div>
<div class="mb-3">
<label for="trainer_id" class="form-label"> Тренер:</label>
    <select class="form-select" name="trainer_id" aria-label="Default select example">
  <?php 
  $sql=$mysqli->query("SELECT * FROM `user` WHERE `role` = 'тренер'");
  foreach($sql as $trainers){
     echo '<option value="'.$trainers['id'].'">'.$trainers['fio'].'</option>';} ?>
</select>
</div>
<div class="mb-3">
<label for="workout_type_id" class="form-label"> Тип тренировки:</label>
    <select class="form-select" name="workout_type_id" aria-label="Default select example">
  <?php 
  $sql = $mysqli->query("SELECT * FROM `workout_types`");
  foreach($sql as $types){
    echo '<option value="'.$types['id'].'">'.$types['title'].' ('.$types['price'].' руб)</option>';} ?>
</select>
</div>
<div class="mb-3">
    <label for="visit_days" class="form-label">Дни посещения:</label>
    <select class="form-select" name="visit_days" aria-label="Default select example">
    <option selected>Выберите день</option>
    <option value="Понедельник">Понедельник</option>
    <option value="Вторник">Вторник</option>
    <option value="Среда">Среда</option>
    <option value="Четверг">Четверг</option>
    <option value="Пятница">Пятница</option>
    <option value="Суббота">Суббота</option>
    <option value="Воскресенье">Воскресенье</option>
</select>
    </div>
    <div class="mb-3">
    <label for="total_workouts" class="form-label">Кол-во тренировок:</label>
    <input type="number" class="form-control" name="total_workouts" id="total_workouts" required>
    </div>
    <div class="mb-3">
    <label for="paid_amount" class="form-label">Сумма оплаты:</label>
    <input type="number" class="form-control" name="paid_amount" min="1" id="paid_amount" required>
    </div>
    <button type="submit" class="btn btn-primary">Отправить договор</button>
</form>
</body>
</html>