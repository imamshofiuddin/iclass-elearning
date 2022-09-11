<?php 
  session_start();
  include '../../config/koneksi.php';
  $username = $_SESSION['username'];
  $id_user = $_SESSION['id'];
  $id_kelas = $_GET['id_kelas'];
  $id_tugas = $_GET['id_tugas'];

  // Hapus Tugas 
  $sql_hapus_assigned_task = "DELETE FROM pengumpulan_tugas WHERE id_tugas='$id_tugas'";
  $result_hapus_assigned_tugas = mysqli_query($conn, $sql_hapus_assigned_task);

  $sql_hapus_tugas = "DELETE FROM tugas WHERE id_tugas='$id_tugas'";
  $result_hapus_tugas = mysqli_query($conn, $sql_hapus_tugas);

  if($result_hapus_tugas){
      echo "<script>
      alert('Tugas Berhasil Dihapus');
      document.location.href='tugas.php?id_kelas=$id_kelas';
      </script>";
  }

?>