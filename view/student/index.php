<?php 
  session_start();
  include '../../config/koneksi.php';
  $id_user = $_SESSION['id'];
  $username = $_SESSION['username'];

  $sql_ambil_kelas = "SELECT peserta_kelas.id_kelas, kelas.nama_kelas, kelas.warna, kelas.id_user, kelas.kode_kelas, user.nama FROM ((peserta_kelas INNER JOIN kelas ON peserta_kelas.id_kelas = kelas.id_kelas) INNER JOIN user ON kelas.id_user = user.id_user) WHERE peserta_kelas.id_user=$id_user";
	$ambil_kelas = mysqli_query($conn,$sql_ambil_kelas);
	$classes = [];

	while ($class = mysqli_fetch_assoc($ambil_kelas)) {
		$classes[] = $class;
	}

  if(isset($_POST['join_kelas'])){
    $inputKode = $_POST['inputKode'];
    $sql_cari_kelas = "SELECT * FROM kelas WHERE kode_kelas='$inputKode'";
    $result_kelas = mysqli_query($conn, $sql_cari_kelas);

    if(mysqli_num_rows($result_kelas) > 0){
      $data_kelas = mysqli_fetch_assoc($result_kelas);
      $id_kelas = $data_kelas['id_kelas'];

      $sql_assign_student = "INSERT INTO peserta_kelas VALUES('','$id_user','$id_kelas','terdaftar')";
      $assign_student = mysqli_query($conn,$sql_assign_student);

      header('Location: index.php');
    } else {
      $kodeSalah = true;
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  
    <link href="https://fonts.googleapis.com/css2?family=Nunito&family=Roboto&display=swap" rel="stylesheet">

    <link href="../../assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <style>
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body{
      font-family: 'Nunito';
      min-height: 100vh;
      background-color: #fff;
    }

    #sidebar{
      width: 250px;
      height: 100%;
      position: fixed;
      background-color: #5863f8;
      transition: 0.4s;
    }

    #sidebar ul li {
      font-size: 12px;
      margin-bottom: 10px;
    }

    #sidebar .icon{
      color: #ffffff;
    }

    #sidebar .icon-title{
      color: #ffffff;
    }

    .card {
      transition: 0.5s;
    }
    
    .card:hover {
      box-shadow: 4px 14px 27px 2px rgba(214,214,214,1);
    }

    .bg-neonblue{
      background-color: #5863f8;
    }

    .header-menu{
      margin-left: 20px;
      font-weight: bold;
      color: #ffffff99;
      font-size: 20px;
    }

    .nav-link{
      font-size: 1.2em;
    }

    .nav-link:focus, .nav-link:hover{
      background-color: #ffffff26;
    }

    #sidebar .active{
      background-color: #ffffff45;
    }

    .my-container{
      transition: 0.4s;
    }

    /* For main section */
    .active-cont{
      margin-left: 250px;
    }

    hr.sidebar-divider {
      margin: 0 1rem 1rem;
    }

    #menu-btn{
      display: none;
      background-color: #00b2e2;
      color: #fff;
    }

    #menu-btn:focus{
      box-shadow: 0 0 0 0.25rem #00b2e2;
    }

    #topbar{
      background-color: white;
      box-shadow: 0px 13px 11px -1px rgba(240,240,240,0.75);
    }

    .kode-mk{
      border-radius: 5px;
      background-color: #5863f8;
      color: white;
      padding: 10px;
      font-size: 1rem;
      text-align: center;
      font-weight: bold;
    }

    .btn-hover {
    width: 5rem;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    height: 35px;
    text-align:center;
    border: none;
    background-size: 300% 100%;
    width: 50%;

    border-radius: 5px;
    -o-transition: all .4s ease-in-out;
    -webkit-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;
  }

  .btn-hover:hover {
    background-position: 100% 0;
    -o-transition: all .4s ease-in-out;
    -webkit-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;
  }

  .btn-hover:focus {
    outline: none;
  }

  .btn-hover.color-blue {
    background-image: linear-gradient(to right, #25aae1, #4481eb, #04befe, #3f86ed);
  }

    @media (max-width: 768px) { 
      #sidebar{
        margin-left: -300px;
      }

      /* For navbar */
      .active-nav{
        margin-left: 0;
      }

      #menu-btn{
        display: block;
      }
    }

    @media (min-width: 769px) { 
      .my-container{
        margin-left: 250px;
      }
    }
  </style>
  </head>
  <body class="bg-light">
  <!-- Sidebar -->
  <nav class="navbar navbar-expand d-flex flex-column align-item-start" id="sidebar">
    <a href="#" class="navbar-brand text-light mt-2">
      <div>
        <img src="../../assets/img/iclass-white.png" style="width: 85px;" alt="">
      </div>
    </a>
    <ul class="navbar-nav d-flex flex-column mt-5 w-100">
      <p class="header-menu">Student</p>
      <hr class="sidebar-divider text-light d-none d-md-block">
      <li class="nav-item w-100">
        <a href="index.php" class="nav-link active text-light ps-4">
          <div class="row">
            <div class="col-2 text-center icon">
              <i class="fa-solid fa-home"></i>
            </div>
            <div class="col-10 icon-title">Home</div>
          </div>
        </a>
      </li>
      <li class="nav-item w-100">
        <a href="#" class="nav-link text-light ps-4">
            <div class="row">
              <div class="col-2 text-center icon">
                <i class="fa-solid fa-user"></i>
              </div>
              <div class="col-10 icon-title">My Profile</div>
            </div>
        </a>
      </li>

      <hr class="sidebar-divider text-light d-none d-md-block">

      <li class="nav-item w-100">
        <a href="../../logout.php" class="nav-link text-light ps-4">
          <div class="row">
            <div class="col-2 text-center icon">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </div>
            <div class="col-10 icon-title">Keluar</div>
          </div>
        </a>
      </li>
    </ul>
  </nav>
  <!-- End Sidebar -->
  
  <section class="my-container">
    <!-- Navbar top -->
    <nav class="navbar navbar-expand-lg sticky-top" id="topbar">
      <div class="container">
        <a class="navbar-brand" href="#"><button class="btn" id="menu-btn">Menu</button></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown pe-1 me-5">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?=$username;?>
                <?php if($_SESSION['jk'] == 'L') : ?> 
                  <img src="../../assets/img/male.png" class="circle" width="40rem">
                <?php else : ?>
                  <img src="../../assets/img/female.png" class="circle" width="40rem">
                <?php endif; ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar top -->

    <!-- Main content -->
    <div class="p-4">
      <div class="container">
        <h3 class="mb-3">Daftar Kelas</h3>
        <button class="btn btn-primary px-3 mb-4" data-bs-toggle="modal" data-bs-target="#join_kelas"><i class="fa-solid fa-plus"></i> Join Kelas</button>
        <div class="row justify-content-start g-2">

        <?php foreach($classes as $class) : ?>
          <div class="col-lg-4 mb-3">
            <div class="card" style="width: 18rem; height: 100%;">
              <div class="card-body d-flex flex-column">
                <div class="row pb-3">
                  <div class="col-9">
                    <h5 class="card-title"><?=$class['nama_kelas'];?></h5>
                    <p class="card-subtitle mb-2 text-muted" style="font-size: 12px;">Pengajar : <?=$class['nama'];?></p>
                  </div>
                  <div class="col-3">
                    <div class="kode-mk" style="background-color: #<?=$class['warna'];?>;"><?=substr($class['nama_kelas'], 0, 2);?></div>
                  </div>
                </div>
                <div class="mt-auto">
                  <a href="kelas.php?id_kelas=<?=$class['id_kelas'];?>" class="card-link me-auto"><button class="btn-hover color-blue">Masuk</button></a>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>

      </div>
    </div>
    <!-- End Main Content -->
    <!-- Modal -->
    <div class="modal fade" id="join_kelas" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-neonblue text-white">
            <h5 class="modal-title fw-bold">Join Kelas</h5>
          </div>
          <div class="modal-body">
            <div class="container">
              <form action="" method="POST">
                <div class="mb-3">
                  <label for="inputKode" class="form-label">Masukkan Kode Kelas</label>
                  <input name="inputKode" type="text" class="form-control" id="inputKode" required>
                </div>
                <button name="join_kelas" type="submit" class="btn btn-primary" style="width: 100%;">Join</button>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal -->
  </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <script>
      var menu_btn = document.querySelector("#menu-btn")
      var sidebar = document.querySelector("#sidebar")
      var container = document.querySelector(".my-container")
      menu_btn.addEventListener("click", () => {
        sidebar.classList.toggle("active-nav")
        container.classList.toggle("active-cont")
      })
    </script>
  </body>
</html>