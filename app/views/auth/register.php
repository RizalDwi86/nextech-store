<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body p-4">

<h2 class="text-center fw-bold">

Register Customer

</h2>

<hr>

<form method="POST">

<div class="mb-3">

<label>Nama</label>

<input type="text" class="form-control" name="nama" required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email" class="form-control" name="email" required>

</div>

<div class="mb-3">

<label>Password</label>

<input type="password" class="form-control" name="password" required>

</div>

<div class="mb-4">

<label>Alamat</label>

<textarea class="form-control" name="alamat" rows="3" required></textarea>

</div>

<button class="btn btn-success w-100">

Register

</button>

</form>

<hr>

<p class="text-center">

Sudah punya akun?

<a href="../../../index.php">

Login

</a>

</p>

</div>

</div>

</div>

</div>

</div>

</body>

</html>