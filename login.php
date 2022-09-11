<?php
  session_start();
  include 'config/koneksi.php';
  //var_dump($_COOKIE); die;
  if(isset($_COOKIE['id']) && isset($_COOKIE['username']) && isset($_COOKIE['level'])){
    $id = $_COOKIE['id'];
    $name = $_COOKIE['username'];
    $level = $_COOKIE['level'];
  
    $result = mysqli_query($conn,"SELECT * FROM user WHERE id_user = '$id'");
    $rows = mysqli_fetch_assoc($result);
  
    if ($name === $rows['username']) {
      $_SESSION['id'] = $id;
      $_SESSION['login'] = true;
      $_SESSION['username'] = $name;
      $_SESSION["level"] = $level;
    }
  }

  if(isset($_SESSION['login']) && isset($_SESSION['username']) && isset($_SESSION['level'])){
    if($_SESSION["level"] == 'student'){
      header('Location: view/student/index.php');
    } else {
      header('Location: view/teacher/index.php');
    } 
    
    exit;
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login iClass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
      body {
        background-image: url('assets/img/seamless-item-school.jpg');
        background-repeat: repeat;
      }

      form img {
        width: 9rem;
      }

      @media (max-width: 991px) {
        .img-bg{
          display: none;
        }
      }
    </style>
  </head>
  <body>
    <div class="container center">
        <div class="row justify-content-center rounded">
            <div class="col-lg-6 col-sm-9 p-5 shadow text-center rounded-start" style="background-color: white;">
              <form action="process/cek-login.php" method="POST">
                <img class="mb-4 mx-auto" src="assets/img/iclass.png" alt="Logo PENS">
                <h3 class="text-start">Login</h3>
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <?php if(isset($_GET['usernotfound'])) : ?>
                      <p style="color: red;"><i>Username '<?=$_GET["user"];?>' tidak ada!</i></p>
                    <?php endif ?>
                </div>
                <div class="mb-3 text-start">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="passwd" required>
                    <?php if(isset($_GET['salahpwd'])){ echo "<p style='color: red;'><i>Password salah!</i></p>";} ?>
                </div>
                <div class="mb-3 form-check text-start">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="rememberme">
                    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                </div>
                <button class="btn-hover color-blue mb-4" name="login">Login</button>
                <p style="font-size: 14px;">Belum memiliki akun ? <a href="registrasi.php">Daftar</a> sekarang.</p>
              </form>
            </div>
            <div class="col-4 rounded-end shadow img-bg" style="background-image: url('assets/img/books.jpg'); background-size: cover;">
              <h1 class="p-5 display-5" style="color: white;">Manage <span>Your Class Materials</span> With iClass !</h1>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>