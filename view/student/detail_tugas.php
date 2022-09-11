<?php 
  session_start();
  include '../../config/koneksi.php';
  $username = $_SESSION['username'];
  $id_user = $_SESSION['id'];
  $id_kelas = $_GET['id_kelas'];
  $id_tugas = $_GET['id_tugas'];

  // Ambil informasi kelas
  $sql_ambil_kelas = "SELECT * FROM kelas WHERE id_kelas='$id_kelas'";
  $ambil_kelas = mysqli_query($conn, $sql_ambil_kelas);
  $class = mysqli_fetch_assoc($ambil_kelas);

  // Ambil data tugas
  $sql_ambil_tugas = "SELECT * FROM tugas WHERE id_tugas='$id_tugas'";
  $ambil_tugas = mysqli_query($conn, $sql_ambil_tugas);
  $data_tugas = mysqli_fetch_assoc($ambil_tugas);

  // Ambil data pengumpulan tugas
  $sql_ambil_pengumpulan = "SELECT * FROM pengumpulan_tugas WHERE id_tugas='$id_tugas' AND id_user='$id_user'";
  $ambil_riwayat_pengumpulan = mysqli_query($conn,$sql_ambil_pengumpulan);
  if(mysqli_num_rows($ambil_riwayat_pengumpulan) > 0){
    $assigned_task = mysqli_fetch_assoc($ambil_riwayat_pengumpulan);
  }

  // Assign Tugas
  if(isset($_POST['assign_tugas'])){
    $catatan = $_POST['catatan'];
    $lampiran = upload();

    $today = date("Y-m-d H:i:s");
    $expire = $data_tugas['deadline']; //from database
    
    $today_time = strtotime($today);
    $expire_time = strtotime($expire);
    
    if ($expire_time >= $today_time) {
      $status = "Tepat Waktu";
    } else {
      $status = "Terlambat";
    }

    if(!$lampiran){
      echo "<script>
      alert('File Gagal Diupload');
      </script>"; 
    } else {
      $sql_insert_file = "INSERT INTO pengumpulan_tugas VALUES('','$id_tugas','$id_user','$lampiran','$catatan','$today','$status', '', 'belum dinilai')";
      $result_insert_file = mysqli_query($conn,$sql_insert_file);
    
      if ($result_insert_file) { 
        echo "<script>
              alert('Tugas Berhasil Disubmit');
              document.location.href='detail_tugas.php?id_kelas=$id_kelas&id_tugas=$id_tugas';
              </script>"; 
      } else { 
        echo "<script>
              alert('Data Gagal Ditambahkan');
              </script>"; 
      }
    }
    
  }

  // Ubah Assigned Tugas 
  if(isset($_POST['update_tugas'])){
    $id_pengumpulan = $_POST['id_pengumpulan'];
    $catatan = $_POST['catatan'];
    $file_lama = $_POST['fileLama'];
    $nilai = $_POST['nilai'];

    if($_FILES['lampiran']['error'] === 4){
      $lampiran = $file_lama;
    } else {
      $lampiran = upload();
    }

    $today = date("Y-m-d H:i:s");
    $expire = $data_tugas['deadline']; //from database
    
    $today_time = strtotime($today);
    $expire_time = strtotime($expire);
    
    if ($expire_time >= $today_time) {
      $status = "Tepat Waktu";
    } else {
      $status = "Terlambat";
    }

    if(!$lampiran){
      echo "<script>
      alert('File Gagal Diupload');
      </script>"; 
    } else {
      $sql_insert_file = "UPDATE pengumpulan_tugas SET 
                          id_pengumpulan = '$id_pengumpulan',
                          id_tugas = '$id_tugas',
                          id_user = '$id_user',
                          file_tugas = '$lampiran',
                          catatan = '$catatan',
                          tgl_submit = '$today',
                          stats = '$status',
                          nilai = '$nilai'
                          WHERE id_tugas = '$id_tugas' AND id_user = '$id_user'
                          ";
      $result_insert_file = mysqli_query($conn,$sql_insert_file);
    
      if ($result_insert_file) { 
        echo "<script>
              alert('Tugas Berhasil Disubmit');
              document.location.href='detail_tugas.php?id_kelas=$id_kelas&id_tugas=$id_tugas';
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

    .text-neonblue{
      color: #5863f8;
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
        <a href="tugas.php?id_kelas=<?=$id_kelas;?>"><button class="border-0 bg-transparent text-neonblue mb-3"><i class="fa-solid fa-arrow-left"></i> Kembali</button></a>
        <h3 class="fw-bold"><?=$data_tugas['judul_tugas'];?></h3>
        <hr>
          <div class="row">
            <div class="col-lg-7">
              <div class="card" style="width: 100%;">
                <div class="card-body">
                  <p class="card-title fw-semibold">Deskripsi :</p>
                  <p class="card-text" style="margin-top: -5px;"><?=$data_tugas['desc_tugas'];?></p>
                  <p class="card-title fw-semibold">Batas Waktu :</p>
                  <p class="card-text" style="margin-top: -5px;"><?=$data_tugas['deadline'];?></p>
                  <?php if(isset($assigned_task)) : ?>
                    <p class="card-title fw-semibold">Waktu Pengumpulan :</p>
                    <p class="card-text" style="margin-top: -5px;"><?=$assigned_task['tgl_submit'];?></p>
                  <?php endif ?>
                  <p class="card-title fw-semibold">Lampiran :</p>
                  <?php if($data_tugas['lampiran'] == '-') : ?>
                    <p>-</p>
                  <?php else :?>
                    <div class="card d-flex flex-column" style="width: 18rem;">
                      <div class="card-body">
                        <i class="fa-solid fa-file me-3"></i> <?=$data_tugas['lampiran'];?>
                        <div class="text-end text-neonblue">
                          <a href="../../process/download.php?file_tugas=<?=$data_tugas['lampiran'];?>"><i class="fa-solid fa-download"></i></a>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  </div>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="card" style="width: 100%;">
                <div class="card-header bg-neonblue text-white">
                  Tugas Anda
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <p style="margin-bottom: -1px;">Catatan : </p>
                    <?php if(isset($assigned_task)) : ?>
                      <small><?=$assigned_task['catatan'];?></small><br>
                    <?php else : ?>
                      <small>-</small>
                    <?php endif; ?>
                    <br>
                    <p>Lampiran : </p>
                    <?php if(isset($assigned_task)) : ?>
                      <div class="card d-flex flex-column mb-3" style="width: 100%;">
                        <div class="card-body">
                          <i class="fa-solid fa-file me-3"></i> <?=$assigned_task['file_tugas'];?>
                          <div class="text-end text-neonblue">
                            <a href="../../process/download.php?file_tugas=<?=$assigned_task['file_tugas'];?>"><i class="fa-solid fa-download"></i></a>
                          </div>
                        </div>
                      </div>
                    <?php else : ?>
                      <small>-</small>
                    <?php endif; ?>

                    <?php if(!isset($assigned_task)) : ?>
                      <button class="btn btn-primary mt-auto bg-neonblue my-2" data-bs-target="#assign_tugas" data-bs-toggle="modal" style="width: 100%; font-size: 14px;">Submit Tugas</button>
                    <?php else : ?>
                      <button class="btn btn-warning my-2" data-bs-target="#assign_tugas" data-bs-toggle="modal" style="width: 100%; font-size: 14px;">Edit Tugas</button>
                    <?php endif; ?>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <?php if(isset($assigned_task)) : ?>
            <div class="card mt-3" style="width: 18rem;">
              <div class="card-body mt-auto">
                <?php if($assigned_task['status_nilai'] == 'dinilai') : ?>
                  <h6><b>Nilai : <?=$assigned_task['nilai'];?>/100</b></h6>
                <?php else : ?>
                  <h6><b>Nilai :</b> <i>Belum dinilai</i></h6>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>
      </div>
    </div>
    <!-- End Main Content -->

    <!-- Modal -->
    <div class="modal fade" id="assign_tugas" tabindex="-1">
      <div class="modal-dialog modal-l">
        <div class="modal-content">
          <div class="modal-header bg-neonblue text-white">
            <h5 class="modal-title fw-bold">Submit Tugas</h5>
          </div>
          <div class="modal-body">
            <div class="container">
              <?php if(isset($assigned_task)) : ?>
                <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="id_pengumpulan" value="<?=$assigned_task['id_pengumpulan'];?>">
                  <input type="hidden" name="fileLama" value="<?=$assigned_task['file_tugas'];?>">
                  <input type="hidden" name="nilai" value="<?=$assigned_task['nilai'];?>">
                  <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" id="catatan" rows="5"><?=$assigned_task['catatan'];?></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran file</label><br>
                    <small>File sebelumnya : <?=$assigned_task['file_tugas'];?></small>
                    <input name="lampiran" type="file" class="form-control" id="lampiran" rows="5"></input>
                  </div>
                  <button name="update_tugas" type="submit" class="btn btn-primary">Submit</button>
                </form>
              <?php else : ?>
                <form action="" method="post" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" id="catatan" rows="5"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran file</label>
                    <input name="lampiran" type="file" class="form-control" id="lampiran" rows="5" required></input>
                  </div>
                  <button name="assign_tugas" type="submit" class="btn btn-primary">Submit</button>
                </form>
              <?php endif; ?>
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