<?php 
  session_start();
  include '../../config/koneksi.php';
  $username = $_SESSION['username'];
  $id_user = $_SESSION['id'];
  $id_kelas = $_GET['id_kelas'];

  // Ambil informasi kelas
  $sql_ambil_kelas = "SELECT * FROM kelas WHERE id_kelas='$id_kelas'";
  $ambil_kelas = mysqli_query($conn, $sql_ambil_kelas);
  $class = mysqli_fetch_assoc($ambil_kelas);

  // Ambil data peserta
  $sql_ambil_peserta = "SELECT peserta_kelas.id_user, user.nama, user.jk FROM peserta_kelas INNER JOIN user ON peserta_kelas.id_user = user.id_user WHERE peserta_kelas.id_kelas=$id_kelas ORDER BY user.nama ASC";
  $result_data_peserta = mysqli_query($conn, $sql_ambil_peserta);
  $jum_peserta = mysqli_num_rows($result_data_peserta);
  
  // Ambil data tugas
  $sql_ambil_tugas = "SELECT * FROM tugas WHERE id_kelas='$id_kelas' ORDER BY id_tugas DESC";
  $ambil_tugas = mysqli_query($conn, $sql_ambil_tugas);

  if(mysqli_num_rows($ambil_tugas) > 0){
    $tasks = [];
    while($task = mysqli_fetch_assoc($ambil_tugas)){
      $tasks[] = $task;
    }
  } else {
    $no_tugas = true;
  }

  // Tambah tugas
  if(isset($_POST['submit_tugas'])){
    $judul_tugas = $_POST['judul_tugas'];
    $deskripsi = $_POST['desc_tugas'];
    $deadline = $_POST['deadline'];
    $lampiran = upload();

    if(!$lampiran){
      echo "<script>
      alert('File Gagal Diupload');
      </script>"; 
    } else {
      $sql_insert_file = "INSERT INTO tugas VALUES('','$judul_tugas','$deskripsi','$lampiran','$deadline','$id_kelas')";
      $result_insert_file = mysqli_query($conn,$sql_insert_file);
    
      if ($result_insert_file) { 
        echo "<script>
              alert('Data Berhasil Ditambahkan');
              document.location.href='tugas.php?id_kelas=$id_kelas';
              </script>"; 
      } else { 
        echo "<script>
              alert('Data Gagal Ditambahkan');
              </script>"; 
      }
    }

  }

  function upload () {
    global $conn;
    $namaFile = $_FILES['lampiran']['name'];
    $ukuranFile = $_FILES['lampiran']['size'];
    $error = $_FILES['lampiran']['error'];
    $tmpName = $_FILES['lampiran']['tmp_name'];

    //cek apakah tidak ada gambar yang dipilih
    if ($error === 4) {
      return '-';
    }
  
    //cek apakah yang diupload bukan gambar
    // $ekstensiGambarValid = ['jpg','jpeg','png'];
    $ekstensiFile = explode('.', $namaFile);
    $ekstensiFile = strtolower(end($ekstensiFile));
  
    // if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    // 	echo "
    // 		<script>
    // 			alert('Yang anda upload bukan gambar!');
    // 			document.location.href = 'tambah.php';
    // 		</script>
    // 	";
    // 	return exit;
    // }
  
    //cek apakah ukuran file terlalu besar 
    if ($ukuranFile > 5000000) {
      echo "
        <script>
          alert('Ukuran file gambar terlalu besar!');
          document.location.href = 'tambah.php';
        </script>
      ";
  
      return exit;
    }
  
    //jika lolos pengecekan, gambar siap diupload 
    // generate nama gambar baru
    $file_name = explode('.',$namaFile);
    $ekstensi = end($file_name);
    $i = 0;
    $ori_file_name = "";
    while($file_name[$i] != $ekstensi){
      $ori_file_name .= $file_name[$i];
      $i++;
    } 

    function generateRandomString($length = 25) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
    
    $sql_file_namasama = "SELECT * FROM tugas WHERE lampiran='$namaFile'";
    $result_file_namasama = mysqli_query($conn, $sql_file_namasama);
    if(mysqli_num_rows($result_file_namasama) == 0){
      $namaFileBaru = $ori_file_name;  
    } else {
      $string = generateRandomString(5);
      $namaFileBaru = $ori_file_name . "_($string)";
    }
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiFile;
  
    move_uploaded_file($tmpName, '../../file/tugas/' . $namaFileBaru);
    return $namaFileBaru;
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher</title>
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

    .card {
      transition: 0.5s;
    }
    
    .card:hover {
      box-shadow: 4px 14px 27px 2px rgba(214,214,214,1);
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
      <p class="header-menu">Teacher</p>
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
        <a href="kelas.php?id_kelas=<?=$id_kelas;?>" class="nav-link text-light ps-4">
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
        <a href="tugas.php?id_kelas=<?=$id_kelas;?>" class="nav-link active text-light ps-4">
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
        <button class="btn btn-primary px-3 mb-4" data-bs-toggle="modal" data-bs-target="#add_tugas"><i class="fa-solid fa-plus"></i> Tambah Tugas</button>
        <div class="row justify-content-start">
          <?php if(!isset($no_tugas)) : ?>
          <?php foreach($tasks as $task) : ?>
          <div class="col-lg-4 mb-3">
            <div class="card d-flex flex-column" style="width: 100%;">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <h5><?=$task['judul_tugas'];?></h5>
                  <small>Deadline : <?=$task['deadline'];?></small>
                  <div class="mt-3 text-end">
                    <?php 
                      $id_tugas=$task['id_tugas']; 
                      $ambil_pengumpulan = mysqli_query($conn, "SELECT * FROM pengumpulan_tugas WHERE id_tugas='$id_tugas'"); 
                      $jumlah_pengumpulan = mysqli_num_rows($ambil_pengumpulan);
                    ?>
                    <small><?=$jumlah_pengumpulan;?>/<?=$jum_peserta;?> Mengumpulkan</small>
                  </div>
                </li>
                <li class="list-group-item ms-auto">
                    <a href="detail_tugas.php?id_tugas=<?=$task['id_tugas']?>&id_kelas=<?=$id_kelas;?>"><button class="btn btn-primary ms-auto" style="display: inline; font-size: 12px;">Detail tugas</button></a>
                </li>
              </ul>
            </div>
          </div>
          <?php endforeach; ?>
          <?php else : ?>
            <p>Belum ada tugas</p>
          <?php endif; ?>
        </div>

      </div>
    </div>
    <!-- End Main Content -->

    <!-- Modal -->
    <div class="modal fade" id="add_tugas" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header bg-neonblue text-white">
            <h5 class="modal-title fw-bold">Tambah Tugas</h5>
          </div>
          <div class="modal-body">
            <div class="container">
              <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="judul" class="form-label">Judul tugas</label>
                  <input name="judul_tugas" type="text" class="form-control" id="judul" required>
                </div>
                <div class="mb-3">
                  <label for="desc" class="form-label">Deskripsi</label>
                  <textarea name="desc_tugas" class="form-control" id="desc" rows="3"></textarea>
                </div>
                <div class="mb-3">
                  <label for="lampiran" class="form-label">Lampiran</label>
                  <input name="lampiran" type="file" class="form-control" id="lampiran" rows="5"></input>
                </div>
                <div class="mb-3">
                  <label for="dl" class="form-label">Deadline</label>
                  <input name="deadline" type="datetime-local" class="form-control" id="dl" rows="5" required></input>
                </div>
                <button name="submit_tugas" type="submit" class="btn btn-primary">Submit</button>
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