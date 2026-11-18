<?php session_start();?>
<nav class="navbar navbar-expand-lg navbar-light" style="background: linear-gradient(90deg, rgba(255, 241, 112, 0.9), rgba(82, 211, 42, 0.9)) !important;">
  <div class="container-fluid">
    
       <a class="navbar-brand d-flex align-items-center text-dark" href="#">
      <img src="img/999-cropped.png" alt="Логотип" width="100" height="100" class="d-inline-block align-text-top rounded-circle me-2" style="object-fit: cover;">
 ТРЕНАЖЁРНЫЙ ЗАЛ
    </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-dark" href="index.php">Главная</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="trener.php">Панель тренера</a>
        </li>
        <li class="nav-item">
        <a class="nav-link text-dark" href="logout.php">Выйти (<?php echo htmlspecialchars($_SESSION['fio']);?>)</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
