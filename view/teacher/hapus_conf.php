<?php 

session_start();
include '../../config/koneksi.php';
$id_kelas = $_GET['id_kelas'];

$sql = "DELETE FROM conference WHERE id_kelas='$id_kelas'";
$result = mysqli_query($conn,$sql);

if($result){
    echo "<script>
    alert('Link Conference Berhasil Dihapus');
    document.location.href='kelas.php?id_kelas=$id_kelas';
    </script>";
} else {
    echo "<script>
    alert('Link Conference Gagal Dihapus');
    document.location.href='kelas.php?id_kelas=$id_kelas';
    </script>";
}

?>