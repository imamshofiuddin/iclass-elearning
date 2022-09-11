<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar iClass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
      body {
        background-image: url('assets/img/seamless-item-school.jpg');
        background-repeat: repeat;
      }

      form img {
        width: 9rem;
      }

      @media (max-width: 991px) {
        .img-bg{
          display: none;
        }
      }
    </style>
  </head>
  <body>
    <div class="my-5 container">
        <div class="row justify-content-center rounded">
            <div class="col-lg-6 col-sm-9 p-5 shadow text-center rounded-start" style="background-color: white;">
              <form action="process/register.php" method="POST">
                <img class="mb-4 mx-auto" src="assets/img/iclass.png" alt="Logo PENS">
                <h3 class="text-start">Daftar</h3>
                <div class="mb-3 text-start">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="jk" class="form-label">Jenis Kelamin</label>
                    <select class="form-control" name="jk" id="jk">
                      <option value="L">Laki-Laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="mb-3 text-start">
                    <label for="level" class="form-label">Daftar sebagai</label><br>
                    <input type="radio" name="level" id="level" value="teacher"> Pengajar
                    <input type="radio" name="level" id="level" value="student"> Siswa
                </div>
                <hr>
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="passwd" class="form-label">Password</label>
                    <input type="password" class="form-control" id="passwd" name="passwd" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="confirmPass" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirmPass" name="confirmPass" required>
                </div>
                <button class="btn-hover color-blue mb-4" name="register">Daftar</button>
              </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>