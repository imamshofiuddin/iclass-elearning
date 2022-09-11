<?php
  include '../config/koneksi.php';
  if(isset($_GET['file_tugas'])){
    $dir="../file/tugas/";
    $filename=$_GET['file_tugas'];
  } else if(isset($_GET['file_materi'])){
    $dir="../file/materi/";
    $filename=$_GET['file_materi'];
  } else {
    echo "File tidak ditemukan";
  }

  $file_path=$dir.$filename;
  $ctype="application/octet-stream";
  //
  if(!empty($file_path) && file_exists($file_path)){ //check keberadaan file
    header("Pragma:public");
    header("Expired:0");
    header("Cache-Control:must-revalidate");
    header("Content-Control:public");
    header("Content-Description: File Transfer");
    header("Content-Type: $ctype");
    header("Content-Disposition:attachment; filename=\"".basename($file_path)."\"");
    header("Content-Transfer-Encoding:binary");
    header("Content-Length:".filesize($file_path));
    flush();
    readfile($file_path);
    exit();
  } 
?>