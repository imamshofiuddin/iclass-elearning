<?php 

include '../config/koneksi.php';

if (isset($_POST["register"])) {

	if (registrasi($_POST) > 0) {
		echo "<script>
		alert('User Baru Berhasil Ditambahkan');
    document.location.href= '../login.php';
		</script>";
	} else {
		echo "<script>
		document.location.href= '../registrasi.php';
		</script>";
	}

}

function registrasi($data) {

	global $conn;
  $nama = htmlspecialchars($data["name"]);
  $email = htmlspecialchars($data["email"]);
  $jk = htmlspecialchars($data["jk"]);
  $level = htmlspecialchars($data["level"]);
	$username = strtolower($data["username"]);
	$password = mysqli_real_escape_string($conn, $data["passwd"]);
	$password2 = mysqli_real_escape_string($conn, $data["confirmPass"]);


	//cek user apakah ada 
	$result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
			alert('user sudah terdaftar, silahkan masukkan username yang belum terdaftar!');
		</script>";

		return false;
	}

	//cek apakah konfirmasi password salah 
	if ($password !== $password2) {
		echo "<script>
			alert('Konfirmasi password salah!');
		</script>";

		return false;
	}

	//enkripsi password 
	$password = password_hash($password, PASSWORD_DEFAULT);

	//masukkan data ke database
	mysqli_query($conn,"INSERT INTO user VALUES('','$nama','$email','$jk','$username','$password','$level')");

	return mysqli_affected_rows($conn);

}

?>