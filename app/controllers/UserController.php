<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return $this->userModel->getAllUsers();
    }

    public function updateRole()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $role = $_POST['role'] ?? null;

            if ($id && $role) {
                $this->userModel->updateRole($id, $role);
            }
        }
        header('Location: ../views/admin/user_list.php');
        exit;
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->userModel->deleteUser($id);
        }
        header('Location: ../views/admin/user_list.php');
        exit;
    }
}

if (isset($_GET['action'])) {
    $controller = new UserController();
    if ($_GET['action'] == 'updateRole') $controller->updateRole();
    if ($_GET['action'] == 'delete') $controller->delete();
}
