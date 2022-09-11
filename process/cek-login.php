<?php 
  session_start();
  include '../config/koneksi.php';

  if(isset($_POST['login'])){
      $username = $_POST["username"];
      $password = $_POST["passwd"];
      $result = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    
      if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
          if(isset($_POST['rememberme'])){
            setcookie('id', $row['id_user'], time()+3600, '/');
            setcookie('username', $row['username'], time()+3600, '/');
            setcookie('level', $row['level'], time()+3600, '/');
          }
          
          $_SESSION["login"] = true;
          $_SESSION["id"] = $row["id_user"];
          $_SESSION['jk'] = $row["jk"];
          $_SESSION["username"] = $row["username"];
          $_SESSION["level"] = $row["level"];
          
          if($_SESSION["level"] == 'student'){
            header('location: ../view/student');
          } else if($_SESSION["level"] == 'teacher'){
            header('location: ../view/teacher');
          } else {
            header('location: ../view/unknown.php');
          }

        } else {
          header('Location: ../login.php?salahpwd=true');
        }
      } else {
        header("Location: ../login.php?usernotfound=true&user=$username");
      }
  }
?>