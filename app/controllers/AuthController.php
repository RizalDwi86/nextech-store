<?php

require_once 'app/core/Database.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $database = new Database();
            $conn = $database->getConnection();

            $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $query->execute([$email]);

            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                session_start();

                $_SESSION['id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == "admin") {

                    header("Location: app/views/dashboard/admin.php");
                    exit;

                } else {

                    header("Location: app/views/dashboard/customer.php");
                    exit;

                }

            } else {

                echo "<script>
                        alert('Email atau Password Salah!');
                        window.location='index.php';
                      </script>";

            }

        } else {

            require_once 'app/views/auth/login.php';

        }
    }

    public function register()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $alamat = trim($_POST['alamat']);
            $role = "customer";

            $database = new Database();
            $conn = $database->getConnection();

            $cek = $conn->prepare("SELECT id FROM users WHERE email=?");
            $cek->execute([$email]);

            if ($cek->rowCount() > 0) {

                echo "<script>
                        alert('Email sudah digunakan!');
                        window.location='register.php';
                      </script>";

                exit;
            }

            $query = $conn->prepare("INSERT INTO users(nama,email,password,role,alamat) VALUES(?,?,?,?,?)");

            $query->execute([
                $nama,
                $email,
                $password,
                $role,
                $alamat
            ]);

            echo "<script>
                    alert('Registrasi berhasil!');
                    window.location='index.php';
                  </script>";

        } else {

            require_once 'app/views/auth/register.php';

        }

    }
}