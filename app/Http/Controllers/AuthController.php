<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;

class AuthController extends Controller
{
    /**
     * Koneksi PDO ke database (sama seperti sebelumnya, dipindah ke sini
     * supaya bisa dipakai ulang oleh showLogin() maupun login()).
     */
    private function getPdo(): PDO
    {
        $db_host = 'localhost';
        $db_name = 'db_tuhomestay';
        $db_user = 'root';
        $db_pass = '';

        $pdo = new PDO(
            "mysql:host=$db_host;charset=utf8mb4",
            $db_user,
            $db_pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$db_name`");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                fullname VARCHAR(100) NOT NULL,
                email VARCHAR(150) NOT NULL UNIQUE,
                phone VARCHAR(20) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        return $pdo;
    }

    /**
     * GET /login — menampilkan form login.
     */
    public function showLogin()
    {
        $errors = [];
        $old = [
            'email' => '',
            'phone' => '',
        ];

        return view('login', compact('errors', 'old'));
    }

    /**
     * POST /login — memproses login.
     */
    public function login(Request $request)
    {
        $errors = [];

        $email    = trim($request->input('email', ''));
        $password = $request->input('password', '');
        $phone    = trim($request->input('phone', ''));
        $remember = $request->boolean('remember');

        $old = compact('email', 'phone');

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Masukkan email yang valid';
        }

        $passwordFilled = $password !== '';
        $phoneFilled    = $phone !== '';

        if (!$passwordFilled && !$phoneFilled) {
            $errors['password'] = 'Isi salah satu: kata sandi atau nomor telepon';
            $errors['phone']    = 'Isi salah satu: kata sandi atau nomor telepon';
        }

        if ($phoneFilled && !preg_match('/^\+?[0-9]{8,15}$/', preg_replace('/[\s-]/', '', $phone))) {
            $errors['phone'] = 'Format nomor telepon tidak valid';
        }

        if (empty($errors)) {
            try {
                $pdo = $this->getPdo();
            } catch (PDOException $e) {
                $errors['general'] = 'Koneksi database gagal: ' . $e->getMessage();
                return view('login', compact('errors', 'old'));
            }

            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            $loginSuccess = false;

            if ($user) {
                if ($passwordFilled && password_verify($password, $user['password'])) {
                    $loginSuccess = true;
                } elseif ($phoneFilled && $user['phone'] === $phone) {
                    $loginSuccess = true;
                }
            }

            if ($loginSuccess) {
                // Pakai session() bawaan Laravel (BUKAN $_SESSION native PHP),
                // supaya session('user') di view lain bisa membacanya.
                session(['user' => [
                    'id'       => $user['id'],
                    'fullname' => $user['fullname'],
                    'email'    => $user['email'],
                    'phone'    => $user['phone'],
                ]]);

                $response = redirect('/');

                if ($remember) {
                    $response->withCookie(cookie('remember_email', $email, 60 * 24 * 30));
                } else {
                    $response->withCookie(cookie()->forget('remember_email'));
                }

                // redirect() (bukan header()+exit()) supaya Laravel sempat
                // menyimpan session-nya lewat siklus response yang normal.
                return $response;
            }

            $errors['general'] = 'Email, kata sandi, atau nomor telepon salah';
        }

        return view('login', compact('errors', 'old'));
    }
}