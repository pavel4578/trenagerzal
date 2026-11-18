<?php
include 'temp/head.php';
include 'temp/nav.php';
include 'temp/bd.php';
?>
<form method="post" action="reg.php">
  <h1 class="mb-3 mt-3">Регистрация</h1>
  <div class="mb-3">
    <label for="fio" class="form-label">ФИО</label>
    <input type="text" class="form-control" id="fio" name="fio"  required pattern="[А-ЯЁа-яё\s\-]+" >
      <div id="fio" class="form-text text-muted">
   Пример ФИО: Иванов Иван Иванович
  </div>
  </div>
  <div class="mb-3">
    <label for="login" class="form-label">Логин</label>
    <input type="text" class="form-control" id="login" name="login" minlength="6" pattern="[a-zA-Z0-9]+" required>
  </div>
  <div class="mb-3">
    <label  for="pass">Пароль</label>
    <input type="password" class="form-control" id="pass" name="password" minlenght="8" required>
  </div>
   <div class="mb-3">
  <label for="email">E-mail</label>
  <input type="email" 
         class="form-control" 
         id="email" 
         name="email" 
         required
         pattern="^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,}$"
       >
    <div id="emailHelp" class="form-text text-muted">
   Пример email: pavel.umka@mail.ru
  </div>
</div>
   <div class="mb-3">
    <label  for="phone">Телефон</label>
   <input type="tel" class="form-control" id="tel" name="phone" placeholder="8(XXX)XXX-XX-XX"  required>
  </div>
  <button type="submit" class="btn" style="background: linear-gradient(90deg, #b8eb44 0%, #64d148 100%); color: #1a1a1a; border: none; font-weight: 500;">Зарегистрировать</button>

</form>

