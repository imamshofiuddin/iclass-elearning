<?php 
  session_start();
  include '../../config/koneksi.php';
  $username = $_SESSION['username'];
  $id_user = $_SESSION['id'];
  $id_kelas = $_GET['id_kelas'];

  // Hapus Kelas
  mysqli_query($conn, "DELETE FROM materi WHERE id_kelas='$id_kelas'");
  mysqli_query($conn, "DELETE FROM peserta_kelas WHERE id_kelas='$id_kelas'");
  mysqli_query($conn, "DELETE FROM conference WHERE id_kelas='$id_kelas'");
  mysqli_query($conn, "DELETE FROM presensi WHERE id_kelas='$id_kelas'");

  $tugas = mysqli_query($conn, "SELECT id_tugas FROM tugas WHERE id_kelas='$id_kelas'");
  $id_tugas = [];
  while($id_t = mysqli_fetch_assoc($tugas)){
    $id_tugas[] = $id_t['id_tugas'];
  }

  for($i=0; $i < count($id_tugas); $i++){
    mysqli_query($conn, "DELETE FROM pengumpulan_tugas WHERE id_tugas='$id_tugas[$i]'");
  }

  mysqli_query($conn, "DELETE FROM tugas WHERE id_kelas='$id_kelas'");

  $sql_hapus_kelas = "DELETE FROM kelas WHERE id_kelas='$id_kelas'";
  $result_hapus_kelas = mysqli_query($conn, $sql_hapus_kelas);

  if($result_hapus_kelas === true){
      echo "<script>
      alert('Kelas Berhasil Dihapus');
      document.location.href='index.php';
      </script>";
  } else {
      echo "<script>
      alert('Kelas Gagal Dihapus');
      document.location.href='index.php';
      </script>";
  }
?>