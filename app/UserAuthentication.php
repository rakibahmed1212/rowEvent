<?php

namespace App;

use App\ProcessDataContainer;

class UserAuthentication
{
    private $process;

    public function __construct()
    {
        $dataProcess = new ProcessDataContainer();
        $this->process = $dataProcess;
        session_start();
    }

    public function register($name, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $columns = '(name,email, password, created_at)';
        $values = "('$name','$email', '$hashedPassword', NOW())";
        return $this->process->createData('users', $columns, $values);
    }

    public function login($email, $password)
    {
        $user = $this->process->getSingleData('users', "email = '$email'") ?? null;
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = 1;
            return true;
        }
        return false;
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }
    public function isAdmin()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['admin']);
    }

    public function logout()
    {
        session_destroy();
    }
}
