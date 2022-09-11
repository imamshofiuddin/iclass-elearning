<?php 
  session_start();
  include '../../config/koneksi.php';
  $username = $_SESSION["username"];
  $id_user = $_SESSION["id"];
  $id_kelas = $_GET["id_kelas"];

  // Ambil data kelas
  $sql_ambil_kelas = "SELECT kelas.nama_kelas, kelas.id_user, user.nama FROM kelas INNER JOIN user ON kelas.id_user = user.id_user WHERE id_kelas='$id_kelas'";
  $ambil_kelas = mysqli_query($conn, $sql_ambil_kelas);
  $class = mysqli_fetch_assoc($ambil_kelas);

  // Ambil data peserta
  $sql_ambil_peserta = "SELECT peserta_kelas.id_user, user.nama, user.jk FROM peserta_kelas INNER JOIN user ON peserta_kelas.id_user = user.id_user WHERE peserta_kelas.id_kelas=$id_kelas ORDER BY user.nama ASC";
  $result_data_peserta = mysqli_query($conn, $sql_ambil_peserta);
  $jum_peserta = mysqli_num_rows($result_data_peserta);

  if($jum_peserta == 0){
    $no_peserta = true;
  } else {
    $participants = [];
    while($participant = mysqli_fetch_assoc($result_data_peserta)){
      $participants[] = $participant;
    }
  }

  // Ambil Data Presensi
  $sql_ambil_presensi = "SELECT * FROM presensi WHERE id_kelas = '$id_kelas' ORDER BY id_presensi DESC";
  $result_ambil_presensi = mysqli_query($conn, $sql_ambil_presensi);
  
  if(mysqli_num_rows($result_ambil_presensi) > 0){
    $presensi_terakhir = mysqli_fetch_assoc($result_ambil_presensi);

    //ambil presensi aktif
    if($presensi_terakhir['stats'] == 'terbuka'){
      $presensi_open = true;
    } else {
      $presensi_tutup = true;
    }
    
  } else {
    $no_presensi = true;
  }

  if(isset($presensi_open)){
    //cek absensi siswa
    $id_presensi = $presensi_terakhir['id_presensi'];
    $sql_presensi_siswa = "SELECT * FROM presensi_siswa WHERE id_presensi = '$id_presensi' AND id_user = '$id_user'";
    $result_presensi_siswa = mysqli_query($conn, $sql_presensi_siswa);

    if(mysqli_num_rows($result_presensi_siswa) > 0){
      $siswa_sudah_absen = true;
    } 
  }

  // SIswa presensi
  if(isset($_POST['do_absen'])){
    $result_do_absen = mysqli_query($conn, "INSERT INTO presensi_siswa VALUES('','$id_presensi','$id_user')");

    if($result_do_absen){
      echo "<script>
      alert('Presensi Berhasil Disimpan');
      document.location.href='kelas.php?id_kelas=$id_kelas';
      </script>"; 
    }
  }

  // Ambil Link Conference 
  $sql_ambil_conf = "SELECT * FROM conference WHERE id_kelas='$id_kelas'";
  $result_ambil_conf = mysqli_query($conn,$sql_ambil_conf);

  if(mysqli_num_rows($result_ambil_conf) > 0){
    $conf = mysqli_fetch_assoc($result_ambil_conf);
  } else {
    $no_conf = true;
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

    .bg-neonblue{
      background-color: #5863f8;
    }

    #sidebar{
      width: 250px;
      height: 100%;
      position: fixed;
      background-color: #5863f8;
      transition: 0.4s;
    }

    .card {
      transition: 0.5s;
    }
    
    .card:hover {
      box-shadow: 4px 14px 27px 2px rgba(214,214,214,1);
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
        <a href="index.php" class="nav-link text-light ps-4">
          <div class="row">
            <div class="col-2 text-center icon">
              <i class="fa-solid fa-home"></i>
            </div>
            <div class="col-10 icon-title">Home</div>
          </div>
        </a>
      </li>
      <hr class="sidebar-divider text-light d-none d-md-block">
      <li class="nav-item w-100">
        <a href="kelas.php?id_kelas=<?=$id_kelas;?>" class="nav-link active text-light ps-4">
          <div class="row">
            <div class="col-2 text-center icon">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="col-10 icon-title">Kelas Online</div>
          </div>
        </a>
      </li>
      <li class="nav-item w-100">
        <a href="materi.php?id_kelas=<?=$id_kelas;?>" class="nav-link text-light ps-4">
            <div class="row">
              <div class="col-2 text-center icon">
                <i class="fa-solid fa-book"></i>
              </div>
              <div class="col-10 icon-title">Materi</div>
            </div>
        </a>
      </li>
      <li class="nav-item w-100">
        <a href="tugas.php?id_kelas=<?=$id_kelas;?>" class="nav-link text-light ps-4">
            <div class="row">
              <div class="col-2 text-center icon">
                <i class="fa-solid fa-square-pen"></i>
              </div>
              <div class="col-10 icon-title">Tugas</div>
            </div>
        </a>
      </li>
      <hr class="sidebar-divider text-light d-none d-md-block">
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
        <h3 class="fw-bold"><?=$class['nama_kelas'];?></h3>
        <hr>
        <div class="row">
          <div class="col">
          <div class="card" style="height: 15rem;">
            <div class="card-body d-flex flex-column">
              <h2 class="card-title">Presensi</h2>
              <h6 class="card-subtitle mb-2 text-muted">Presensi akan terbuka jika pengajar telah membuka presensi</h6><br>
              <h5>Nama Pengajar : <?=$class['nama']?></h5>
              <small>Presensi terakhir pengajar : 
                <?php if(isset($no_presensi)) : ?>
                  -
                <?php else : ?>
                  <?=$presensi_terakhir['open_presensi'];?>
                <?php endif; ?>
              </small>
              <div class="mt-auto">
                <?php if(isset($presensi_tutup) || isset($siswa_sudah_absen) || isset($no_presensi)) : ?>
                  <button class="btn btn-secondary" disabled>Presensi</button>
                <?php else : ?>
                  <form style="display: inline;" action="" method="post">
                    <button name="do_absen" type="submit" class="btn bg-neonblue btn-primary">Presensi</button>
                  </form>
                <?php endif; ?>
                <?php if(isset($conf)) : ?>
                  <a target="_blank" href="<?=$conf['link'];?>" class="card-link"><button class="btn bg-neonblue btn-primary"><i class="fa-solid fa-video me-2"></i> Masuk Conference</button></a>
                <?php else : ?>
                    <button class="btn btn-secondary ms-2" disabled><i class="fa-solid fa-video me-2"></i> Masuk Conference</button>
                    <small>*Pengajar belum membuat conference</small>
                <?php endif ?>
              </div>
            </div>
          </div>
          </div>

        </div>

        <h3 class="mt-5">Peserta</h3>
        <?php if(!isset($no_peserta)) : ?>
        <p>Jumlah : <?=$jum_peserta;?></p>
        <table class="table table-striped table-hover">
          <thead>
            <th>Nomor</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
          </thead>
          <tbody>
          <?php $i = 1; ?>
          <?php foreach($participants as $participant) : ?>
            <tr>
              <td><?=$i;?></td>
              <td><?=$participant['nama'];?></td>
              <td><?=$participant['jk'];?></td>
            </tr>
          <?php $i++; ?>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php else : ?>
          <p>Belum ada peserta dalam kelas ini.</p>
        <?php endif; ?>
      </div>
    </div>
    <!-- End Main Content -->
    <!-- Modal -->
    <div class="modal fade" id="add_conference" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-neonblue text-white">
            <h5 class="modal-title fw-bold">Buat Link Conference</h5>
          </div>
          <div class="modal-body">
            <div class="container">
              <form>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Link</label>
                  <input name="link_conf" type="text" class="form-control" id="exampleInputEmail1" required>
                </div>
                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
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