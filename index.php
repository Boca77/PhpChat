<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Document</title>
  <style>
    #loginBtn.active, #registerBtn.active {
        background-color: white;
    }
  </style>
</head>

<body class="d-flex flex-column justify-content-center min-vh-100">

  <div class="container mt-5 text-center">
    <h1>
      <img src="./imgs/logo.png" class="img-fluid" style="max-width: 200px;" alt="php Logo">
    </h1>
  </div>

  <div class="container mt-4">
    <div class="mx-auto border rounded-3 p-4 bg-light" style="max-width: 720px;">
      <div class="d-flex justify-content-center mb-4">
        <button id="loginBtn" class="btn btn-light flex-fill me-2">Login</button>
        <button id="registerBtn" class="btn flex-fill">Register</button>
      </div>
      <?php
      if (isset($_GET['error'])) {
        echo '<p class="text-danger text-center border border-danger p-2 bg-light rounded mt-3">' . $_GET['errorLogin'] . '</p>';
      }
      ?>
      <div id="login">
        <form class="mx-auto" style="max-width: 480px;" action="login.php" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['old']['email'] ?? '' ?>">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
          <?php
          if (isset($_GET['errorLogin'])) {
            echo '<p class="text-danger text-center border border-danger p-2 bg-light rounded mt-3">' . $_GET['errorLogin'] . '</p>';
          }
          ?>
          <div class="text-center mt-4">
            <button class="btn btn-primary w-50">Login</button>
          </div>
        </form>
      </div>

      <div id="register" style="display: none;">
        <form class="mx-auto" style="max-width: 480px;" action="register.php" method="post">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $_SESSION['old']['name'] ?? '' ?>">
          </div>
          <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" value="<?= $_SESSION['old']['surname'] ?? '' ?>">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['old']['email'] ?? '' ?>">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
          <?php
          if (isset($_GET['errorRegister'])) {
            echo '<p class="text-danger text-center border border-danger p-2 bg-light rounded mt-3">' . $_GET['errorRegister'] . '</p>';
          }
          ?>
          <div class="text-center mt-4">
            <button class="btn btn-primary w-50">Register</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script>
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginForm = document.getElementById('login');
    const registerForm = document.getElementById('register');

    const formStatus = sessionStorage.getItem("formStatus") || "login";
    switchTab(formStatus);

    loginBtn.addEventListener('click', () => switchTab('login'));
    registerBtn.addEventListener('click', () => switchTab('register'));

    function switchTab(tab) {
        sessionStorage.setItem("formStatus", tab);

        if (tab === "login") {
            loginBtn.classList.add('active');
            registerBtn.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        } else {
            registerBtn.classList.add('active');
            loginBtn.classList.remove('active');
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        }
    }

  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
