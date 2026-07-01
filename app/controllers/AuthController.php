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

                    echo "Dashboard customer akan dibuat nanti.";

                }

            } else {

                echo "<script>
                        alert('Email atau Password salah!');
                        window.location='index.php';
                      </script>";

            }

        } else {

            require_once 'app/views/auth/login.php';

        }
    }
}