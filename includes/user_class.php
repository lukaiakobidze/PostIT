<?php
class User {
    public $email;
    public $username;
    public $role;

    public function __construct($email, $username, $role = 'user') {
        $this->email = $email;
        $this->username = $username;
        $this->role = $role;
    }

    public function register($password) {
        $file = __DIR__ . '/../data/users/' . md5($this->email) . '.json';
        if (file_exists($file)) return false;

        $data = [
            'email' => $this->email,
            'username' => $this->username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $this->role
        ];

        file_put_contents($file, json_encode($data));
        return true;
    }

    public static function login($email, $password) {
        $file = __DIR__ . '/../data/users/' . md5($email) . '.json';
        if (!file_exists($file)) return false;

        $data = json_decode(file_get_contents($file), true);
        if (password_verify($password, $data['password'])) {
            return new User($data['email'], $data['username'], $data['role']);
        }
        return false;
    }
}